<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Expediente;
use App\Models\Seguimiento;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    /**
     * Verifica que el cliente pertenezca al abogado autenticado.
     */
    private function verificarCliente(Cliente $cliente): void
    {
        abort_unless(
            (int) $cliente->abogado_id === (int) auth()->id(),
            403
        );
    }

    /**
     * Verifica que el expediente pertenezca a un cliente
     * del abogado autenticado.
     */
    private function verificarExpediente(Expediente $expediente): void
    {
        $expediente->loadMissing('cliente');

        abort_unless(
            $expediente->cliente &&
            (int) $expediente->cliente->abogado_id === (int) auth()->id(),
            403
        );
    }

    // MOSTRAR EXPEDIENTE
    public function show(Expediente $expediente)
    {
        $this->verificarExpediente($expediente);

        return view('expedientes.show', compact('expediente'));
    }

    // CREAR
    public function store(Request $request, Cliente $cliente)
    {
        $this->verificarCliente($cliente);

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

    // EDITAR
    public function edit(Expediente $expediente)
    {
        $this->verificarExpediente($expediente);

        return view('expedientes.edit', compact('expediente'));
    }

    // ACTUALIZAR
    public function update(Request $request, Expediente $expediente)
    {
        $this->verificarExpediente($expediente);

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
        $this->verificarExpediente($expediente);

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
    $expediente->loadMissing('cliente');

    $user = auth()->user();

    abort_unless($user && $expediente->cliente, 403);

    if ($user->role === 'abogado') {
        abort_unless(
            (int) $expediente->cliente->abogado_id === (int) $user->id,
            403
        );
    } elseif ($user->role === 'cliente') {
        abort_unless(
            (int) $expediente->cliente->user_id === (int) $user->id,
            403
        );
    } else {
        abort(403);
    }

    return view('expedientes.imprimir', compact('expediente'));
}
}