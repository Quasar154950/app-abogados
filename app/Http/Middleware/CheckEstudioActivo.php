<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckEstudioActivo
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Permitir logout aunque esté suspendido
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

        // Si está inactivo → lo bloqueamos
        if (!$user->activo) {
            return response()->view('suspendido');
        }

        // Si tiene fecha de vencimiento y ya venció → lo bloqueamos
        if ($user->fecha_vencimiento && now()->greaterThan($user->fecha_vencimiento)) {
            return response()->view('suspendido');
        }

        return $next($request);
    }
}