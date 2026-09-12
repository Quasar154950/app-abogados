<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SoporteApiController extends Controller
{
    /**
     * Devuelve los estudios con sus abogados.
     */
    public function estudios(): JsonResponse
    {
        $estudios = Estudio::query()
            ->with([
                'abogados' => function ($query) {
                    $query
                        ->where('role', 'abogado')
                        ->select([
                            'id',
                            'name',
                            'email',
                            'role',
                            'activo',
                            'estudio_id',
                        ])
                        ->orderBy('name');
                },
            ])
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
                'slug',
                'activo',
                'fecha_vencimiento',
                'plan',
                'precio_suscripcion',
            ]);

        return response()->json([
            'ok' => true,
            'producto' => 'abogados',
            'total_estudios' => $estudios->count(),
            'total_abogados' => $estudios->sum(
                fn ($estudio) => $estudio->abogados->count()
            ),
            'estudios' => $estudios,
        ]);
    }

    /**
     * Renueva la suscripción de un estudio por 30 días.
     */
    public function renovar(Estudio $estudio): JsonResponse
    {
        $fechaBase = $estudio->fecha_vencimiento
            && $estudio->fecha_vencimiento->isFuture()
                ? $estudio->fecha_vencimiento
                : now();

        $estudio->fecha_vencimiento = $fechaBase->copy()->addDays(30);
        $estudio->activo = true;
        $estudio->save();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Suscripción del estudio renovada correctamente.',
            'estudio' => [
                'id' => $estudio->id,
                'nombre' => $estudio->nombre,
                'activo' => $estudio->activo,
                'fecha_vencimiento' => optional(
                    $estudio->fecha_vencimiento
                )->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Activa o suspende un estudio.
     */
    public function toggleActivo(Estudio $estudio): JsonResponse
    {
        $estudio->activo = !$estudio->activo;
        $estudio->save();

        return response()->json([
            'ok' => true,
            'mensaje' => $estudio->activo
                ? 'Estudio activado correctamente.'
                : 'Estudio suspendido correctamente.',
            'estudio' => [
                'id' => $estudio->id,
                'nombre' => $estudio->nombre,
                'activo' => $estudio->activo,
            ],
        ]);
    }

    /**
     * Edita los datos de la suscripción de un estudio.
     */
    public function actualizarSuscripcion(
        Request $request,
        Estudio $estudio
    ): JsonResponse {
        $datos = $request->validate([
            'fecha_vencimiento' => ['required', 'date'],
            'plan' => ['nullable', 'string', 'max:50'],
            'precio_suscripcion' => ['required', 'numeric', 'min:0'],
        ]);

        $estudio->fecha_vencimiento = $datos['fecha_vencimiento'];
        $estudio->plan = $datos['plan'] ?? null;
        $estudio->precio_suscripcion = $datos['precio_suscripcion'];
        $estudio->save();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Suscripción del estudio actualizada correctamente.',
            'estudio' => [
                'id' => $estudio->id,
                'nombre' => $estudio->nombre,
                'fecha_vencimiento' => optional(
                    $estudio->fecha_vencimiento
                )->format('Y-m-d'),
                'plan' => $estudio->plan,
                'precio_suscripcion' => $estudio->precio_suscripcion,
            ],
        ]);
    }
}