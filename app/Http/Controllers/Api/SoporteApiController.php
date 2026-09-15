<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudio;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;

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
            ])
            ->map(function ($estudio) {
                $estudio->acceso_url = url('/estudio/' . $estudio->slug);

                return $estudio;
            });

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

    /**
     * Actualiza nombre y email de un abogado desde soporte.
     */
    public function actualizarAdministrador(
        Request $request,
        User $user
    ): JsonResponse {
        if ($user->role !== 'abogado') {
            return response()->json([
                'ok' => false,
                'mensaje' => 'El usuario seleccionado no es un abogado.',
            ], 422);
        }

        $datos = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->name = $datos['name'];
        $user->email = $datos['email'];
        $user->save();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Administrador actualizado correctamente.',
            'usuario' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'estudio_id' => $user->estudio_id,
            ],
        ]);
    }

    /**
     * Restablece la contraseña de un abogado.
     */
    public function resetPassword(
        User $user
    ): JsonResponse {
        if ($user->role !== 'abogado') {
            return response()->json([
                'ok' => false,
                'mensaje' => 'El usuario seleccionado no es un abogado.',
            ], 422);
        }

        $nuevaPassword = $user->resetearPassword();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Contraseña restablecida correctamente.',
            'usuario' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'password' => $nuevaPassword,
        ]);
    }

    /**
     * Genera un acceso temporal para ver el sistema como un abogado.
     */
    public function verComo(
        User $user
    ): JsonResponse {
        if ($user->role !== 'abogado') {
            return response()->json([
                'ok' => false,
                'mensaje' => 'El usuario seleccionado no es un abogado.',
            ], 422);
        }

        $url = URL::temporarySignedRoute(
            'soporte.acceso-temporal',
            now()->addMinutes(2),
            [
                'user' => $user->id,
            ]
        );

        return response()->json([
            'ok' => true,
            'mensaje' => 'Acceso temporal generado correctamente.',
            'usuario' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'url' => $url,
        ]);
    }
}