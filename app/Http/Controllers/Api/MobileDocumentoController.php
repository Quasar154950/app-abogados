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
        
        $totalBytes = $cliente
            ->getMedia('archivos')
            ->sum('size');

        $espacioUsadoMb = round(
            $totalBytes / 1024 / 1024,
            1
        );

        $documentos = $cliente
    ->getMedia('archivos')
    ->sortByDesc('created_at')
    ->map(function ($documento) {
        $subidoPor = $documento
            ->getCustomProperty('subido_por', 'estudio');

        return [
            'id' => $documento->id,
            'nombre' => $documento->name,
            'archivo' => $documento->file_name,
            'mime_type' => $documento->mime_type,
            'tamano' => $documento->size,
            'url' => in_array(
                strtolower(pathinfo($documento->file_name, PATHINFO_EXTENSION)),
                ['doc', 'docx', 'xls', 'xlsx']
            )
                ? str_replace(
                    '/image/upload/',
                    '/raw/upload/',
                    $documento->getUrl()
                )
                : $documento->getUrl(),

            'subido_por' => $subidoPor,

            'abierto_por_cliente' => (bool) $documento
                ->getCustomProperty(
                    'abierto_por_cliente',
                    false
                ),

            'revisado_por_estudio' => (bool) $documento
                ->getCustomProperty(
                    'revisado_por_estudio',
                    false
                ),

            'fecha' => $documento->created_at?->format('d/m/Y'),
            'hora' => $documento->created_at?->format('H:i'),
        ];
    })
    ->values();

        return response()->json([
            'cantidad' => $documentos->count(),
            'espacio_usado_mb' => $espacioUsadoMb,
            'espacio_limite_mb' => 300,
            'documentos' => $documentos,
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

    $request->validate([
        'archivo' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
    ]);

    $totalBytes = $cliente
        ->fresh()
        ->getMedia('archivos')
        ->sum('size');

    $nuevoArchivo = $request
        ->file('archivo')
        ->getSize();

    $limiteCliente = 300 * 1024 * 1024;

    if (($totalBytes + $nuevoArchivo) > $limiteCliente) {
        return response()->json([
            'message' => 'Este cliente alcanzó el límite de 300 MB en documentos.',
        ], 422);
    }

    $archivo = $request->file('archivo');

    $nombreOriginal = $archivo->getClientOriginalName();

    $nombreSinExtension = pathinfo(
        $nombreOriginal,
        PATHINFO_FILENAME
    );

    $extension = strtolower(
        $archivo->getClientOriginalExtension()
    );

    $nombreLimpio = str($nombreSinExtension)
        ->ascii()
        ->replaceMatches('/[^A-Za-z0-9_\-]/', '_')
        ->toString();

    $esRaw = in_array(
        $extension,
        ['doc', 'docx', 'xls', 'xlsx']
    );

    $nombreFinal = $esRaw
        ? $nombreLimpio . '.' . $extension
        : $nombreLimpio;

    $media = $cliente
        ->addMedia($archivo->getRealPath())
        ->usingName($nombreOriginal)
        ->usingFileName($nombreFinal)
        ->withCustomProperties([
            'subido_por' => 'cliente',
            'revisado_por_estudio' => false,
        ])
        ->toMediaCollection(
            'archivos',
            'cloudinary'
        );

    return response()->json([
        'message' => 'Documento enviado al estudio con éxito.',
        'documento' => [
            'id' => $media->id,
            'nombre' => $media->name,
            'archivo' => $media->file_name,
            'tamano' => $media->size,
            'url' => $esRaw
                ? str_replace(
                    '/image/upload/',
                    '/raw/upload/',
                    $media->getUrl()
                )
                : $media->getUrl(),
        ],
    ], 201);
}

    public function destroy(Request $request, $documentoId)
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

    if (
        $documento->getCustomProperty(
            'subido_por',
            'estudio'
        ) !== 'cliente'
    ) {
        return response()->json([
            'message' => 'Solo podés eliminar documentos que hayas enviado vos.',
        ], 403);
    }

    $documento->delete();

    return response()->json([
        'message' => 'Documento eliminado correctamente.',
        'documento_id' => (int) $documentoId,
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