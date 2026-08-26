<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MobileExpedienteController extends Controller
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
                'expedientes' => [],
            ], 404);
        }

        $expedientes = $cliente->expedientes()
            ->latest()
            ->get()
            ->map(function ($expediente) {
                return [
                    'id' => $expediente->id,
                    'numero_expediente' => $expediente->numero_expediente,
                    'juzgado' => $expediente->juzgado,
                    'caratula' => $expediente->caratula,
                    'tipo' => $expediente->tipo,
                    'estado' => $expediente->estado,
                    'fecha_inicio' => $expediente->fecha_inicio,
                    'observaciones' => $expediente->observaciones,
                ];
            })
            ->values();

        return response()->json([
            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'email' => $cliente->email,
            ],
            'cantidad' => $expedientes->count(),
            'expedientes' => $expedientes,
        ]);
    }
}
