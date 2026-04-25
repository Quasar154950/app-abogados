<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Expediente;
use App\Models\Seguimiento;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    // MOSTRAR EXPEDIENTE
    public function show(Expediente $expediente)
    {
        return view('expedientes.show', compact('expediente'));
    }

    // CREAR
    public function store(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'numero_expediente' => 'nullable|string|max:255',
            'juzgado' => 'nullable|string|max:255',
            'caratula' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:100',
            'estado' => 'required|in:iniciado,en_tramite,finalizado,archivado',
            'fecha_inicio' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $cliente->expedientes()->create($validated);

        return redirect()
            ->route('clientes.show', $cliente->id)
            ->with('success', 'Expediente agregado correctamente.');
    }

    // EDITAR (mostrar formulario)
    public function edit(Expediente $expediente)
    {
        return view('expedientes.edit', compact('expediente'));
    }

    // ACTUALIZAR
    public function update(Request $request, Expediente $expediente)
    {
        $validated = $request->validate([
            'numero_expediente' => 'nullable|string|max:255',
            'juzgado' => 'nullable|string|max:255',
            'caratula' => 'required|string|max:255',
            'tipo' => 'nullable|string|max:100',
            'estado' => 'required|in:iniciado,en_tramite,finalizado,archivado',
            'fecha_inicio' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $expediente->update($validated);

        return redirect()
            ->route('clientes.show', $expediente->cliente_id)
            ->with('success', 'Expediente actualizado correctamente.');
    }

    // ELIMINAR
    public function destroy(Expediente $expediente)
    {
        $clienteId = $expediente->cliente_id;

        Seguimiento::where('expediente_id', $expediente->id)->delete();

        $expediente->delete();

        return redirect()
            ->route('clientes.show', $clienteId)
            ->with('success', 'Expediente y sus tareas asociadas eliminados correctamente.');
    }

    // IMPRIMIR
    public function imprimir(Expediente $expediente)
    {
        return view('expedientes.imprimir', compact('expediente'));
    }
}