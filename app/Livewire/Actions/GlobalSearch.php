<?php

namespace App\Livewire\Actions;

use App\Models\Cliente;
use App\Models\Nota;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $query = '';
    public $results = ['clientes' => [], 'notas' => []];
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

        $abogadoId = auth()->id();

        $this->results['clientes'] = Cliente::where('abogado_id', $abogadoId)
            ->where('archivado', false)
            ->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->query . '%')
                    ->orWhere('email', 'like', '%' . $this->query . '%')
                    ->orWhere('telefono', 'like', '%' . $this->query . '%');
            })
            ->limit(5)
            ->get()
            ->toArray();

        $this->results['notas'] = Nota::whereHas('cliente', function ($q) use ($abogadoId) {
                $q->where('abogado_id', $abogadoId)
                    ->where('archivado', false);
            })
            ->where('contenido', 'like', '%' . $this->query . '%')
            ->with('cliente')
            ->limit(5)
            ->get()
            ->map(function ($nota) {
                return [
                    'cliente_id' => $nota->cliente_id,
                    'cliente_nombre' => $nota->cliente->nombre ?? 'Desconocido',
                    'contenido' => $nota->contenido,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.actions.global-search');
    }
}