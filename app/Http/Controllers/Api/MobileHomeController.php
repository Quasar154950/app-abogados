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

return response()->json([
    'cliente' => [
        'id' => $cliente->id,
        'nombre' => $cliente->nombre,
        'email' => $cliente->email,
    ],

    'cantidad_expedientes' => $cliente->expedientes()->count(),

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
]);
    }
}
