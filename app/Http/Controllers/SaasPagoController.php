<?php

namespace App\Http\Controllers;

use App\Models\SaasPago;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class SaasPagoController extends Controller
{
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

        try {
            $preference = $client->create([
                'items' => [[
                    'title' => 'Suscripción SaaS MCTandil - ' . strtoupper($user->plan ?? 'PLAN'),
                    'quantity' => 1,
                    'currency_id' => 'ARS',
                    'unit_price' => (int) $user->precio_suscripcion,
                ]],
                'external_reference' => $pago->external_reference,
                'back_urls' => [
                    'success' => url('/suscripcion'),
                    'failure' => url('/suscripcion'),
                    'pending' => url('/suscripcion'),
                ],
                'auto_return' => 'approved',
                'notification_url' => url('/webhooks/mercadopago/saas'),
            ]);

            $pago->update([
                'checkout_url' => $preference->init_point,
            ]);

            return redirect($preference->init_point);

        } catch (\Throwable $e) {
            $pago->update(['estado' => 'error']);

            return redirect('/suscripcion?error=No se pudo generar el pago');
        }
    }
}
