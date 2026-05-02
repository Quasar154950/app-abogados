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

        // Si no está logueado, dejamos pasar
        if (!$user) {
            return $next($request);
        }

        // Si está inactivo → lo bloqueamos
        if (!$user->activo) {
            return response()->view('suspendido');
        }

        // Si tiene fecha de vencimiento y ya venció → lo bloqueamos
        if ($user->fecha_vencimiento && Carbon::today()->gt(Carbon::parse($user->fecha_vencimiento))) {
            return response()->view('suspendido');
        }

        return $next($request);
    }
}