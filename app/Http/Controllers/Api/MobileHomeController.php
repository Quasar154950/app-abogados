<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        $expedienteDestacado = $cliente->expedientes()
    ->latest()
    ->first();

$ultimoSeguimiento = null;

if ($expedienteDestacado) {
    $ultimoSeguimiento = $expedienteDestacado
        ->seguimientos()
        ->latest()
        ->first();
}

$novedades = collect();

// Movimientos
$cliente->seguimientos()
    ->where('visible_para_cliente', true)
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
                ? $seguimiento->created_at->locale('es')->diffForHumans()
                : null,
            'expediente' => $seguimiento->expediente?->numero_expediente,
        ]);
    });

// Documentos
$cliente->getMedia('archivos')
    ->each(function ($documento) use ($novedades) {
        $novedades->push([
            'tipo' => 'documento',
            'titulo' => 'Documento disponible',
            'descripcion' => $documento->name,
            'fecha' => $documento->created_at,
            'fecha_humana' => optional($documento->created_at)
                ? $documento->created_at->locale('es')->diffForHumans()
                : null,
        ]);
    });

// Mensajes del estudio
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
                ? $mensaje->created_at->locale('es')->diffForHumans()
                : null,
        ]);
    });

$novedades = $novedades
    ->sortByDesc('fecha')
    ->take(3)
    ->values()
    ->map(function ($novedad) {
        $novedad['fecha'] = optional($novedad['fecha'])->format('Y-m-d H:i:s');

        return $novedad;
    });

return response()->json([
    'cliente' => [
        'id' => $cliente->id,
        'nombre' => $cliente->nombre,
        'email' => $cliente->email,
    ],

    'cantidad_expedientes' => $cliente->expedientes()->count(),

    'cantidad_mensajes' => $cliente->mensajes()
    ->where('leido', false)
    ->count(),

    'cantidad_documentos' => $cliente->getMedia('archivos')->count(),

    'expediente_destacado' => $expedienteDestacado ? [
        'id' => $expedienteDestacado->id,
        'numero_expediente' => $expedienteDestacado->numero_expediente,
        'caratula' => $expedienteDestacado->caratula,
        'tipo' => $expedienteDestacado->tipo,
        'estado' => $expedienteDestacado->estado,
        'juzgado' => $expedienteDestacado->juzgado,
        'fecha_inicio' => $expedienteDestacado->fecha_inicio,
    ] : null,

    'ultimo_movimiento' => $ultimoSeguimiento ? [
        'descripcion' => $ultimoSeguimiento->descripcion,
        'estado' => $ultimoSeguimiento->estado,
        'fecha' => optional($ultimoSeguimiento->created_at)->format('Y-m-d'),
        'fecha_humana' => optional($ultimoSeguimiento->created_at)
    ? $ultimoSeguimiento->created_at->locale('es')->diffForHumans()
    : null,
    ] : null,
'ultimas_novedades' => $novedades,

]);
    }
}
