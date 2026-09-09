<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudio;
use Illuminate\Http\JsonResponse;

class SoporteApiController extends Controller
{
    /**
     * Devuelve los estudios con sus abogados.
     *
     * Por ahora es SOLO LECTURA.
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
}
