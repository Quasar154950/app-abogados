<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MobileDocumentoController extends Controller
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
                'documentos' => [],
            ], 404);
        }

        $documentos = $cliente
            ->getMedia('archivos')
            ->filter(function ($documento) {
                return $documento->getCustomProperty('subido_por') === 'estudio';
            })
            ->sortByDesc('created_at')
            ->map(function ($documento) {
                return [
                    'id' => $documento->id,
                    'nombre' => $documento->name,
                    'archivo' => $documento->file_name,
                    'mime_type' => $documento->mime_type,
                    'tamano' => $documento->size,
                    'url' => $documento->getUrl(),
                    'abierto_por_cliente' => (bool) $documento
                        ->getCustomProperty('abierto_por_cliente', false),
                    'fecha' => $documento->created_at?->format('d/m/Y'),
                    'hora' => $documento->created_at?->format('H:i'),
                ];
            })
            ->values();

        return response()->json([
            'cantidad' => $documentos->count(),
            'documentos' => $documentos,
        ]);
    }

    public function marcarComoAbierto(Request $request, $documentoId)
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

        $documento = $cliente
            ->getMedia('archivos')
            ->firstWhere('id', (int) $documentoId);

        if (!$documento) {
            return response()->json([
                'message' => 'Documento no encontrado.',
            ], 404);
        }

        if ($documento->getCustomProperty('subido_por') !== 'estudio') {
            return response()->json([
                'message' => 'Documento no disponible.',
            ], 403);
        }

        $documento->setCustomProperty('abierto_por_cliente', true);
        $documento->save();

        return response()->json([
            'message' => 'Documento marcado como abierto.',
            'documento_id' => $documento->id,
        ]);
    }
}