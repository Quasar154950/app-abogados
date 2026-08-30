<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MensajeCliente;
use Illuminate\Http\Request;

class MobileMensajeController extends Controller
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
                'mensajes' => [],
            ], 404);
        }

        $mensajesPendientes = $cliente
            ->mensajes()
            ->where('remitente', 'estudio')
            ->where('leido', false);

        if ($cliente->chat_borrado_cliente_at) {
            $mensajesPendientes->where(
                'created_at',
                '>',
                $cliente->chat_borrado_cliente_at
            );
        }

        $mensajesPendientes->update([
            'leido' => true,
            'leido_at' => now(),
        ]);

        $query = $cliente
            ->mensajes()
            ->oldest();

        if ($cliente->chat_borrado_cliente_at) {
            $query->where(
                'created_at',
                '>',
                $cliente->chat_borrado_cliente_at
            );
        }

        $mensajes = $query
            ->get()
            ->map(function (MensajeCliente $mensaje) {
                return [
                    'id' => $mensaje->id,
                    'mensaje' => $mensaje->mensaje,
                    'remitente' => $mensaje->remitente,
                    'es_mio' => $mensaje->remitente === 'cliente',
                    'leido' => (bool) $mensaje->leido,
                    'leido_at' => $mensaje->leido_at?->toISOString(),
                    'fecha' => $mensaje->created_at?->format('d/m/Y'),
                    'hora' => $mensaje->created_at?->format('H:i'),
                ];
            })
            ->values();

        return response()->json([
            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'email' => $cliente->email,
            ],
            'cantidad' => $mensajes->count(),
            'mensajes' => $mensajes,
        ]);
    }

    public function store(Request $request)
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

        $data = $request->validate([
            'mensaje' => ['required', 'string', 'min:2'],
        ]);

        $mensaje = MensajeCliente::create([
            'cliente_id' => $cliente->id,
            'user_id' => $user->id,
            'mensaje' => trim($data['mensaje']),
            'remitente' => 'cliente',
        ]);

        return response()->json([
            'message' => 'Mensaje enviado correctamente.',
            'mensaje' => [
                'id' => $mensaje->id,
                'mensaje' => $mensaje->mensaje,
                'remitente' => $mensaje->remitente,
                'es_mio' => true,
                'leido' => (bool) $mensaje->leido,
                'leido_at' => $mensaje->leido_at?->toISOString(),
                'fecha' => $mensaje->created_at?->format('d/m/Y'),
                'hora' => $mensaje->created_at?->format('H:i'),
            ],
        ], 201);
    }

    public function clear(Request $request)
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

        $cliente->chat_borrado_cliente_at = now();
        $cliente->save();

        return response()->json([
            'message' => 'La conversación fue limpiada correctamente.',
        ]);
    }
}