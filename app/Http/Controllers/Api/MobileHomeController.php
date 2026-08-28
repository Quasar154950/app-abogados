<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MobileHomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'cliente') {
            return response()->json([
                'message' => 'Acceso no autorizado.',
            ], 403);
        }

        $cliente = $user->cliente;

        if (!$cliente) {
            return response()->json([
                'message' => 'No se encontró un cliente vinculado a esta cuenta.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | EXPEDIENTES
        |--------------------------------------------------------------------------
        |
        | Iniciado    = activo
        | En trámite  = activo
        | Finalizado  = visible, pero no activo
        | Archivado   = no visible en la app
        |
        */

        $normalizarEstado = function ($estado) {
            return Str::of($estado ?? '')
                ->ascii()
                ->lower()
                ->replace([' ', '-'], '_')
                ->toString();
        };

        $expedientes = $cliente->expedientes()->get();

        $expedientesActivos = $expedientes->filter(function ($expediente) use ($normalizarEstado) {
            return in_array(
                $normalizarEstado($expediente->estado),
                ['iniciado', 'en_tramite'],
                true
            );
        });

        $expedientesVisibles = $expedientes->filter(function ($expediente) use ($normalizarEstado) {
            return $normalizarEstado($expediente->estado) !== 'archivado';
        });

        /*
        |--------------------------------------------------------------------------
        | EXPEDIENTE DESTACADO
        |--------------------------------------------------------------------------
        |
        | Prioridad:
        | 1. En trámite
        | 2. Iniciado
        | 3. Finalizado
        | 4. Archivado nunca
        |
        | Si hay varios con el mismo estado, se toma el actualizado más
        | recientemente.
        |
        */

        $prioridadEstados = [
            'en_tramite' => 1,
            'iniciado' => 2,
            'finalizado' => 3,
        ];

        $expedienteDestacado = $expedientesVisibles
            ->sort(function ($a, $b) use ($normalizarEstado, $prioridadEstados) {

                $estadoA = $normalizarEstado($a->estado);
                $estadoB = $normalizarEstado($b->estado);

                $prioridadA = $prioridadEstados[$estadoA] ?? 99;
                $prioridadB = $prioridadEstados[$estadoB] ?? 99;

                if ($prioridadA !== $prioridadB) {
                    return $prioridadA <=> $prioridadB;
                }

                return optional($b->updated_at)->timestamp
                    <=> optional($a->updated_at)->timestamp;
            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | ÚLTIMO MOVIMIENTO DEL EXPEDIENTE DESTACADO
        |--------------------------------------------------------------------------
        |
        | Un movimiento es algo que YA ocurrió.
        | No debe tomar registros de Agenda.
        |
        */

        $ultimoSeguimiento = null;

        if ($expedienteDestacado) {
            $ultimoSeguimiento = $expedienteDestacado
                ->seguimientos()
                ->where('visible_para_cliente', true)
                ->whereNull('fecha_recordatorio')
                ->latest()
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | ÚLTIMAS NOVEDADES
        |--------------------------------------------------------------------------
        */

        $novedades = collect();

        /*
        | MOVIMIENTOS
        | Solo visibles para el cliente y que no sean Agenda.
        */

        $cliente->seguimientos()
            ->where('visible_para_cliente', true)
            ->whereNull('fecha_recordatorio')
            ->with('expediente')
            ->latest()
            ->get()
            ->each(function ($seguimiento) use ($novedades) {

                $novedades->push([
                    'tipo' => 'movimiento',
                    'titulo' => 'Nuevo movimiento',
                    'descripcion' => $seguimiento->descripcion,
                    'fecha' => $seguimiento->created_at,

                    'fecha_humana' => optional($seguimiento->created_at)
                        ? $seguimiento->created_at
                            ->locale('es')
                            ->diffForHumans()
                        : null,

                    'expediente' => $seguimiento->expediente?->numero_expediente,
                ]);
            });

        /*
        | DOCUMENTOS
        | Solamente documentos subidos por el estudio.
        */

        $cliente->getMedia('archivos')
            ->filter(function ($documento) {
                return $documento->getCustomProperty('subido_por') === 'estudio';
            })
            ->each(function ($documento) use ($novedades) {

                $novedades->push([
                    'tipo' => 'documento',
                    'titulo' => 'Documento disponible',
                    'descripcion' => $documento->name,
                    'fecha' => $documento->created_at,

                    'fecha_humana' => optional($documento->created_at)
                        ? $documento->created_at
                            ->locale('es')
                            ->diffForHumans()
                        : null,
                ]);
            });

        /*
        | MENSAJES
        | Solo mensajes enviados por el estudio.
        */

        $cliente->mensajes()
            ->where('remitente', 'estudio')
            ->latest()
            ->get()
            ->each(function ($mensaje) use ($novedades) {

                $novedades->push([
                    'tipo' => 'mensaje',
                    'titulo' => 'Nuevo mensaje',
                    'descripcion' => $mensaje->mensaje,
                    'fecha' => $mensaje->created_at,

                    'fecha_humana' => optional($mensaje->created_at)
                        ? $mensaje->created_at
                            ->locale('es')
                            ->diffForHumans()
                        : null,
                ]);
            });

        /*
        | Solamente las últimas 3 novedades, sin importar el tipo.
        */

        $novedades = $novedades
            ->sortByDesc('fecha')
            ->take(3)
            ->values()
            ->map(function ($novedad) {

                $novedad['fecha'] = optional($novedad['fecha'])
                    ->format('Y-m-d H:i:s');

                return $novedad;
            });

        /*
        |--------------------------------------------------------------------------
        | PRÓXIMA FECHA IMPORTANTE
        |--------------------------------------------------------------------------
        |
        | Solo:
        | - visible para cliente
        | - futura
        | - no completada
        | - con fecha de recordatorio
        |
        */

        $proximaFechaImportante = $cliente->seguimientos()
            ->where('visible_para_cliente', true)
            ->whereNotNull('fecha_recordatorio')
            ->where('estado', '!=', 'resuelto')
            ->whereDate(
                'fecha_recordatorio',
                '>=',
                now()->toDateString()
            )
            ->with([
                'etiqueta',
                'expediente',
            ])
            ->orderBy('fecha_recordatorio')
            ->orderByRaw('hora_recordatorio IS NULL')
            ->orderBy('hora_recordatorio')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | CONTADORES DEL HOME
        |--------------------------------------------------------------------------
        */

        $cantidadMensajesNoLeidos = $cliente->mensajes()
            ->where('remitente', 'estudio')
            ->where('leido', false)
            ->count();

        /*
        | Por ahora cuenta documentos del estudio.
        |
        | El siguiente ajuste será agregar el concepto de
        | "visto por el cliente" para que este número represente
        | exclusivamente documentos nuevos/no abiertos.
        */

        $cantidadDocumentosEstudio = $cliente
            ->getMedia('archivos')
            ->filter(function ($documento) {
                return $documento->getCustomProperty('subido_por') === 'estudio';
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'email' => $cliente->email,
            ],

            /*
            | Solo expedientes activos:
            | Iniciado + En trámite.
            */

            'cantidad_expedientes' => $expedientesActivos->count(),

            /*
            | Solo mensajes del estudio no leídos.
            */

            'cantidad_mensajes' => $cantidadMensajesNoLeidos,

            /*
            | Por ahora: documentos subidos por el estudio.
            | Luego lo convertiremos en documentos no vistos.
            */

            'cantidad_documentos' => $cantidadDocumentosEstudio,

            /*
            | Expediente destacado.
            */

            'expediente_destacado' => $expedienteDestacado ? [

                'id' => $expedienteDestacado->id,

                'numero_expediente' =>
                    $expedienteDestacado->numero_expediente,

                'caratula' =>
                    $expedienteDestacado->caratula,

                'tipo' =>
                    $expedienteDestacado->tipo,

                'estado' =>
                    $expedienteDestacado->estado,

                'juzgado' =>
                    $expedienteDestacado->juzgado,

                'fecha_inicio' =>
                    $expedienteDestacado->fecha_inicio,

            ] : null,

            /*
            | Último movimiento real del expediente destacado.
            */

            'ultimo_movimiento' => $ultimoSeguimiento ? [

                'descripcion' =>
                    $ultimoSeguimiento->descripcion,

                'estado' =>
                    $ultimoSeguimiento->estado,

                'fecha' => optional(
                    $ultimoSeguimiento->created_at
                )->format('Y-m-d'),

                'fecha_humana' => optional(
                    $ultimoSeguimiento->created_at
                )
                    ? $ultimoSeguimiento->created_at
                        ->locale('es')
                        ->diffForHumans()
                    : null,

            ] : null,

            /*
            | Últimas 3 novedades.
            */

            'ultimas_novedades' => $novedades,

            /*
            | Próxima fecha importante.
            */

            'proxima_fecha_importante' =>
                $proximaFechaImportante ? [

                    'id' =>
                        $proximaFechaImportante->id,

                    'fecha' => optional(
                        $proximaFechaImportante->fecha_recordatorio
                    )->format('Y-m-d'),

                    'dia' => optional(
                        $proximaFechaImportante->fecha_recordatorio
                    )->format('d'),

                    'mes' => optional(
                        $proximaFechaImportante->fecha_recordatorio
                    )
                        ? strtoupper(
                            $proximaFechaImportante
                                ->fecha_recordatorio
                                ->locale('es')
                                ->translatedFormat('M')
                        )
                        : null,

                    'hora' =>
                        $proximaFechaImportante->hora_recordatorio
                            ? substr(
                                $proximaFechaImportante->hora_recordatorio,
                                0,
                                5
                            )
                            : null,

                    'titulo' =>
                        $proximaFechaImportante
                            ->etiqueta?->nombre
                        ?? 'Próxima fecha importante',

                    'descripcion' =>
                        $proximaFechaImportante->descripcion,

                    'expediente' =>
                        $proximaFechaImportante
                            ->expediente?->numero_expediente,

                    'juzgado' =>
                        $proximaFechaImportante
                            ->expediente?->juzgado,

                ] : null,

        ]);
    }
}
