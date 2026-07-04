<?php

namespace App\Http\Controllers;

use App\Models\SaasPago;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class SaasPagoController extends Controller
{
    private string $baseUrl = 'https://rare-prosperity-production-a81a.up.railway.app';

    public function pagar(User $user)
    {
        $soporte = auth()->user();

        if (!$soporte || $soporte->email !== 'soporte@tuempresa.com') {
            abort(403);
        }

        if ($user->email === 'soporte@tuempresa.com') {
            abort(403);
        }

        if (!$user->precio_suscripcion || $user->precio_suscripcion <= 0) {
            return redirect('/soporte?error=Este cliente no tiene precio de suscripción configurado');
        }

        $accessToken = env('MERCADOPAGO_SAAS_ACCESS_TOKEN');

        if (!$accessToken) {
            return redirect('/soporte?error=Falta configurar Mercado Pago SaaS');
        }

        $pago = SaasPago::create([
            'user_id' => $user->id,
            'plan' => $user->plan,
            'monto' => $user->precio_suscripcion,
            'estado' => 'pendiente',
            'external_reference' => 'saas_pago_' . $user->id . '_' . now()->timestamp,
        ]);

        MercadoPagoConfig::setAccessToken($accessToken);

        $client = new PreferenceClient();

        $payload = [
            'items' => [[
                'title' => 'Suscripción SaaS MCTandil - ' . strtoupper($user->plan),
                'quantity' => 1,
                'currency_id' => 'ARS',
                'unit_price' => (int) $user->precio_suscripcion,
            ]],
            'external_reference' => $pago->external_reference,
            'back_urls' => [
                'success' => $this->baseUrl . '/soporte/saas-pagos/exito',
                'failure' => $this->baseUrl . '/soporte/saas-pagos/error',
                'pending' => $this->baseUrl . '/soporte/saas-pagos/pendiente',
            ],
            'auto_return' => 'approved',
            'notification_url' => $this->baseUrl . '/webhooks/mercadopago/saas',
        ];

        Log::info('MP SaaS payload soporte', $payload);

        try {
            $preference = $client->create($payload);

            $pago->update([
                'checkout_url' => $preference->init_point,
            ]);

            return redirect('/soporte?success=Link de pago SaaS generado correctamente');

        } catch (MPApiException $e) {
            Log::error('MP SaaS API error soporte', [
                'message' => $e->getMessage(),
                'api_response' => method_exists($e, 'getApiResponse') ? $e->getApiResponse() : null,
            ]);

            $pago->update(['estado' => 'error']);

            return redirect('/soporte?error=Mercado Pago respondió con error al crear el link');

        } catch (\Throwable $e) {
            Log::error('MP SaaS error general soporte', [
                'message' => $e->getMessage(),
            ]);

            $pago->update(['estado' => 'error']);

            return redirect('/soporte?error=No se pudo generar el link de pago');
        }
    }

    public function pagarMiSuscripcion()
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'abogado') {
            abort(403);
        }

        if (!$user->precio_suscripcion || $user->precio_suscripcion <= 0) {
            return redirect('/suscripcion?error=No tenés precio de suscripción configurado');
        }

        $accessToken = env('MERCADOPAGO_SAAS_ACCESS_TOKEN');

        if (!$accessToken) {
            return redirect('/suscripcion?error=Falta configurar Mercado Pago SaaS');
        }

        $pago = SaasPago::create([
            'user_id' => $user->id,
            'plan' => $user->plan,
            'monto' => $user->precio_suscripcion,
            'estado' => 'pendiente',
            'external_reference' => 'saas_pago_' . $user->id . '_' . now()->timestamp,
        ]);

        MercadoPagoConfig::setAccessToken($accessToken);

        $client = new PreferenceClient();

        $payload = [
            'items' => [[
                'title' => 'Suscripción SaaS MCTandil - ' . strtoupper($user->plan),
                'quantity' => 1,
                'currency_id' => 'ARS',
                'unit_price' => (int) $user->precio_suscripcion,
            ]],
            'external_reference' => $pago->external_reference,
            'back_urls' => [
                'success' => $this->baseUrl . '/suscripcion',
                'failure' => $this->baseUrl . '/suscripcion',
                'pending' => $this->baseUrl . '/suscripcion',
            ],
            'auto_return' => 'approved',
            'notification_url' => $this->baseUrl . '/webhooks/mercadopago/saas',
        ];

        Log::info('MP SaaS payload mi suscripcion', $payload);

        try {
            $preference = $client->create($payload);

            $pago->update([
                'checkout_url' => $preference->init_point,
            ]);

            return redirect($preference->init_point);

        } catch (MPApiException $e) {
            Log::error('MP SaaS API error mi suscripcion', [
                'message' => $e->getMessage(),
                'api_response' => method_exists($e, 'getApiResponse') ? $e->getApiResponse() : null,
            ]);

            $pago->update(['estado' => 'error']);

            return redirect('/suscripcion?error=Mercado Pago respondió con error al crear el pago');

        } catch (\Throwable $e) {
            Log::error('MP SaaS error general mi suscripcion', [
                'message' => $e->getMessage(),
            ]);

            $pago->update(['estado' => 'error']);

            return redirect('/suscripcion?error=No se pudo generar el pago');
        }
    }

    public function exito()
    {
        return redirect('/soporte?success=Pago iniciado correctamente');
    }

    public function error()
    {
        return redirect('/soporte?error=El pago fue cancelado o rechazado');
    }

    public function pendiente()
    {
        return redirect('/soporte?success=El pago quedó pendiente de aprobación');
    }
}