<?php

namespace App\Livewire\Actions;

use App\Models\Cliente;
use App\Models\Nota; // Asegurate de tener el modelo Nota importado
use Livewire\Component;

class GlobalSearch extends Component
{
    public $query = '';
    public $results = ['clientes' => [], 'notas' => []]; // Estructura organizada
    public $showModal = false;

    protected $listeners = ['open-global-search' => 'openSearch'];

    public function openSearch()
    {
        $this->showModal = true;
        $this->reset(['query', 'results']);
    }

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = ['clientes' => [], 'notas' => []];
            return;
        }

        // 1. Buscamos Clientes (que no estén archivados)
        $this->results['clientes'] = Cliente::where('archivado', false)
            ->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->query . '%')
                  ->orWhere('email', 'like', '%' . $this->query . '%')
                  ->orWhere('telefono', 'like', '%' . $this->query . '%');
            })
            ->limit(5)
            ->get()
            ->toArray();

        // 2. Buscamos en el CONTENIDO de las Notas (mostrando el dueño de la nota)
        $this->results['notas'] = Nota::where('contenido', 'like', '%' . $this->query . '%')
            ->with('cliente') // Traemos la relación para saber de quién es
            ->whereHas('cliente', function($q) {
                $q->where('archivado', false); // Solo notas de clientes no archivados
            })
            ->limit(5)
            ->get()
            ->map(function($nota) {
                return [
                    'cliente_id' => $nota->cliente_id,
                    'cliente_nombre' => $nota->cliente->nombre ?? 'Desconocido',
                    'contenido' => $nota->contenido
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.actions.global-search');
    }
}
