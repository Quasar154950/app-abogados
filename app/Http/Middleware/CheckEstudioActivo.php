<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEstudioActivo
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Permitir logout aunque el estudio esté suspendido
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        // Soporte NO se bloquea nunca
        if ($user && $user->email === 'soporte@tuempresa.com') {
            return $next($request);
        }

        // Si no está logueado, dejamos pasar
        if (!$user) {
            return $next($request);
        }

        $estudio = null;

        // ABOGADO: toma su estudio directamente
        if ($user->role === 'abogado') {
            $estudio = $user->estudio;
        }

        // CLIENTE: toma el estudio de su abogado
        if ($user->role === 'cliente') {
            $cliente = $user->cliente;

            if ($cliente && $cliente->abogado) {
                $estudio = $cliente->abogado->estudio;
            }
        }

        // Si no encontramos estudio, bloqueamos por seguridad
        if (!$estudio) {
            return response()->view('suspendido');
        }

        // Si el estudio está inactivo
        if (!$estudio->activo) {
            return response()->view('suspendido');
        }

        // Si la suscripción del estudio venció
        if (
            $estudio->fecha_vencimiento &&
            now()->greaterThan($estudio->fecha_vencimiento)
        ) {
            return response()->view('suspendido');
        }

        return $next($request);
    }
}