<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seguimiento;
use App\Models\Etiqueta;
use App\Models\Cliente;
use App\Models\Expediente;

class SeguimientoController extends Controller
{
    public function index(Request $request)
    {
        $abogadoId = auth()->id();
        $filtros = $request->all();

        $query = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })->with(['cliente', 'etiqueta', 'expediente']);

        if (!empty($filtros['cliente_id'])) {
            $query->where('cliente_id', $filtros['cliente_id']);
        }

        if (!empty($filtros['expediente_id'])) {
            $query->where('expediente_id', $filtros['expediente_id']);
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['etiqueta_id'])) {
            $query->where('etiqueta_id', $filtros['etiqueta_id']);
        }

        if (!empty($filtros['prioridad'])) {
            $query->where('prioridad', $filtros['prioridad']);
        }

        if (!empty($filtros['vencimiento'])) {
            if ($filtros['vencimiento'] === 'vencido') {
                $query->where('fecha_recordatorio', '<', now()->startOfDay())
                      ->where('estado', '!=', 'resuelto');
            } elseif ($filtros['vencimiento'] === 'hoy') {
                $query->whereDate('fecha_recordatorio', now()->today());
            }
        }

        $seguimientos = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // 🔥 SOLO datos del abogado
        $clientes = Cliente::where('abogado_id', $abogadoId)->orderBy('nombre')->get();

        $expedientes = Expediente::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })->orderBy('caratula')->get();

        $etiquetas = Etiqueta::orderBy('nombre')->get();

        return view('seguimientos.index', compact(
            'seguimientos',
            'clientes',
            'etiquetas',
            'expedientes',
            'filtros'
        ));
    }

    public function store(Request $request)
    {
        $abogadoId = auth()->id();

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'expediente_id' => 'nullable|exists:expedientes,id',
            'descripcion' => 'required|string',
            'estado' => 'required|in:pendiente,en_curso,resuelto',
            'prioridad' => 'required|in:baja,media,alta',
            'etiqueta_id' => 'nullable|exists:etiquetas,id',
            'fecha_recordatorio' => 'nullable|date',
        ]);

        // 🔥 validar que el cliente pertenece al abogado
        $cliente = Cliente::where('abogado_id', $abogadoId)
            ->findOrFail($request->cliente_id);

        Seguimiento::create([
            'cliente_id' => $cliente->id,
            'expediente_id' => $request->expediente_id ?: null,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
            'prioridad' => $request->prioridad,
            'etiqueta_id' => $request->etiqueta_id,
            'fecha_recordatorio' => $request->fecha_recordatorio ?: null,
        ]);

        return back()->with('success', 'Tarea agregada correctamente');
    }

    public function edit(Seguimiento $seguimiento)
    {
        $this->checkOwnership($seguimiento);

        $etiquetas = Etiqueta::all();
        $expedientes = $seguimiento->cliente->expedientes()->orderBy('caratula')->get();

        return view('seguimientos.edit', compact('seguimiento', 'etiquetas', 'expedientes'));
    }

    public function update(Request $request, Seguimiento $seguimiento)
    {
        $this->checkOwnership($seguimiento);

        $request->validate([
            'expediente_id' => 'nullable|exists:expedientes,id',
            'descripcion' => 'required|string',
            'estado' => 'required|in:pendiente,en_curso,resuelto',
            'prioridad' => 'required|in:baja,media,alta',
            'etiqueta_id' => 'nullable|exists:etiquetas,id',
            'fecha_recordatorio' => 'nullable|date',
        ]);

        $seguimiento->update([
            'expediente_id' => $request->expediente_id ?: null,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
            'prioridad' => $request->prioridad,
            'etiqueta_id' => $request->etiqueta_id,
            'fecha_recordatorio' => $request->fecha_recordatorio ?: null,
        ]);

        return redirect()
            ->route('clientes.show', $seguimiento->cliente_id)
            ->with('success', 'Tarea actualizada correctamente');
    }

    public function destroy(Seguimiento $seguimiento)
    {
        $this->checkOwnership($seguimiento);

        $clienteId = $seguimiento->cliente_id;
        $seguimiento->delete();

        return redirect()
            ->route('clientes.show', $clienteId)
            ->with('success', 'Tarea eliminada correctamente');
    }

    public function cambiarEstado(Request $request, Seguimiento $seguimiento)
    {
        $this->checkOwnership($seguimiento);

        $request->validate([
            'estado' => 'required|in:pendiente,en_curso,resuelto',
        ]);

        $seguimiento->update([
            'estado' => $request->estado,
        ]);

        return back()->with('success', 'Estado actualizado correctamente');
    }

    // 🔥 FUNCIÓN CLAVE DE SEGURIDAD
    private function checkOwnership(Seguimiento $seguimiento)
    {
        if ($seguimiento->cliente->abogado_id !== auth()->id()) {
            abort(403);
        }
    }
}