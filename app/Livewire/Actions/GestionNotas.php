<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use App\Models\Nota;
use App\Models\Cliente;

class GestionNotas extends Component
{
    public Cliente $cliente;
    public $contenido = '';

    // Esta función guarda la nota sin recargar
    public function agregarNota()
    {
        $this->validate(['contenido' => 'required']);
        
        $this->cliente->notas()->create([
            'contenido' => $this->contenido,
            'is_pinned' => false
        ]);

        $this->contenido = ''; // Limpia el buscador
        $this->cliente->load('notas'); // Refresca la lista en pantalla
    }

    // Esta función maneja el pin 📌
    public function togglePin($id)
    {
        $nota = Nota::find($id);
        $nota->update([
            'is_pinned' => !$nota->is_pinned,
            'updated_at' => now()
        ]);
        $this->cliente->load('notas');
    }

    public function borrar($id)
    {
        Nota::destroy($id);
        $this->cliente->load('notas');
    }

    public function render()
    {
        return view('livewire.actions.gestion-notas', [
            'notas' => $this->cliente->notas()
                ->orderByDesc('is_pinned')
                ->orderByDesc('updated_at')
                ->get()
        ]);
    }
}