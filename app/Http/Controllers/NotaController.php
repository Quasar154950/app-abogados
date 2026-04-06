<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;

class NotaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string',
            'cliente_id' => 'required|exists:clientes,id',
        ], [
            'contenido.required' => 'La nota no puede estar vacía.',
        ]);

        Nota::create([
            'cliente_id' => $request->cliente_id,
            'contenido' => $request->contenido,
        ]);

        return redirect()->back()->with('success', 'Nota agregada correctamente.');
    }

    // 👉 Mostrar pantalla de edición
    public function edit(Nota $nota)
    {
        return view('notas.edit', compact('nota'));
    }

    // 👉 Guardar cambios
    public function update(Request $request, Nota $nota)
    {
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
        $nota->delete();

        return redirect()->back()->with('success', 'Nota eliminada correctamente.');
    }

    // 📌 Fijar / desfijar nota
    public function togglePin(Nota $nota)
    {
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