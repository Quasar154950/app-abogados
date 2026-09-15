<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudio;
use App\Models\SaasPago;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class SoporteApiController extends Controller
{
    /**
     * URL pública de la aplicación Abogados.
     */
    private string $baseUrl = 'https://rare-prosperity-production-a81a.up.railway.app';

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

    /**
     * Genera un link de pago SaaS para un estudio desde soporte central.
     */
    public function cobrarSaas(
        Estudio $estudio
    ): JsonResponse {
        if (!$estudio->precio_suscripcion || $estudio->precio_suscripcion <= 0) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Este estudio no tiene precio de suscripción configurado.',
            ], 422);
        }

        $abogado = $estudio->abogados()
            ->where('role', 'abogado')
            ->orderBy('id')
            ->first();

        if (!$abogado) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Este estudio no tiene abogados asociados.',
            ], 422);
        }

        $accessToken = env('MERCADOPAGO_SAAS_ACCESS_TOKEN');

        if (!$accessToken) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Falta configurar Mercado Pago SaaS.',
            ], 500);
        }

        $pago = SaasPago::create([
            'user_id' => $abogado->id,
            'estudio_id' => $estudio->id,
            'plan' => $estudio->plan,
            'monto' => $estudio->precio_suscripcion,
            'estado' => 'pendiente',
            'external_reference' =>
                'saas_estudio_' . $estudio->id . '_' . now()->timestamp,
        ]);

        MercadoPagoConfig::setAccessToken($accessToken);

        $client = new PreferenceClient();

        $payload = [
            'items' => [[
                'title' =>
                    'Suscripción SaaS MCTandil - '
                    . strtoupper($estudio->plan ?? 'PLAN'),
                'quantity' => 1,
                'currency_id' => 'ARS',
                'unit_price' => (int) $estudio->precio_suscripcion,
            ]],

            'external_reference' => $pago->external_reference,

            'back_urls' => [
                'success' => $this->baseUrl . '/suscripcion',
                'failure' => $this->baseUrl . '/suscripcion',
                'pending' => $this->baseUrl . '/suscripcion',
            ],

            'auto_return' => 'approved',

            'notification_url' =>
                $this->baseUrl . '/webhooks/mercadopago/saas',
        ];

        Log::info('MP SaaS payload soporte central', $payload);

        try {
            $preference = $client->create($payload);

            $pago->update([
                'checkout_url' => $preference->init_point,
            ]);

            return response()->json([
                'ok' => true,
                'mensaje' => 'Link de pago SaaS generado correctamente.',
                'pago' => [
                    'id' => $pago->id,
                    'estudio_id' => $estudio->id,
                    'estudio' => $estudio->nombre,
                    'plan' => $estudio->plan,
                    'monto' => $estudio->precio_suscripcion,
                    'estado' => $pago->estado,
                    'checkout_url' => $preference->init_point,
                ],
            ]);

        } catch (MPApiException $e) {
            Log::error('MP SaaS API error soporte central', [
                'message' => $e->getMessage(),
                'api_response' => method_exists($e, 'getApiResponse')
                    ? $e->getApiResponse()
                    : null,
            ]);

            $pago->update([
                'estado' => 'error',
            ]);

            return response()->json([
                'ok' => false,
                'mensaje' => 'Mercado Pago respondió con error al crear el link.',
            ], 502);

        } catch (\Throwable $e) {
            Log::error('MP SaaS error general soporte central', [
                'message' => $e->getMessage(),
            ]);

            $pago->update([
                'estado' => 'error',
            ]);

            return response()->json([
                'ok' => false,
                'mensaje' => 'No se pudo generar el link de pago.',
            ], 500);
        }
    }
}