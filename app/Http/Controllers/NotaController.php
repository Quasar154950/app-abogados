<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Nota;

class NotaController extends Controller
{
    /**
     * Verifica que la nota pertenezca a un cliente
     * del abogado autenticado.
     */
    private function verificarNota(Nota $nota): void
    {
        $nota->loadMissing('cliente');

        abort_unless(
            $nota->cliente &&
            (int) $nota->cliente->abogado_id === (int) auth()->id(),
            403
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string',
            'cliente_id' => 'required|exists:clientes,id',
        ], [
            'contenido.required' => 'La nota no puede estar vacía.',
        ]);

        $cliente = Cliente::where('id', $request->cliente_id)
            ->where('abogado_id', auth()->id())
            ->firstOrFail();

        Nota::create([
            'cliente_id' => $cliente->id,
            'contenido' => $request->contenido,
        ]);

        return redirect()->back()->with('success', 'Nota agregada correctamente.');
    }

    // Mostrar pantalla de edición
    public function edit(Nota $nota)
    {
        $this->verificarNota($nota);

        return view('notas.edit', compact('nota'));
    }

    // Guardar cambios
    public function update(Request $request, Nota $nota)
    {
        $this->verificarNota($nota);

        $request->validate([
            'contenido' => 'required|string',
        ], [
            'contenido.required' => 'La nota no puede estar vacía.',
        ]);

        $nota->update([
            'contenido' => $request->contenido,
        ]);

        return redirect()->route('clientes.show', $nota->cliente_id)
            ->with('success', 'Nota actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $nota = Nota::findOrFail($id);

        $this->verificarNota($nota);

        $nota->delete();

        return redirect()->back()->with('success', 'Nota eliminada correctamente.');
    }

    // Fijar / desfijar nota
    public function togglePin(Nota $nota)
    {
        $this->verificarNota($nota);

        $nuevoEstado = !$nota->is_pinned;

        $nota->update([
            'is_pinned' => $nuevoEstado,
        ]);

        $mensaje = $nuevoEstado
            ? 'Nota fijada correctamente.'
            : 'Nota desfijada correctamente.';

        return redirect()->back()->with('success', $mensaje);
    }
}