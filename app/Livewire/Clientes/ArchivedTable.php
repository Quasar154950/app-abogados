<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class ArchivedTable extends Component
{
    use WithPagination;

    public $busqueda = '';

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function desarchivar($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $cliente->update(['archivado' => false]);
            session()->flash('success', 'Cliente restaurado a la lista activa.');
        }
    }

    public function delete($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $cliente->delete();
            session()->flash('success', 'Cliente eliminado definitivamente.');
        }
    }

    public function render()
    {
        $clientes = Cliente::query()
            ->where('archivado', true) // Solo los archivados
            ->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('email', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('telefono', 'like', '%' . $this->busqueda . '%');
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.clientes.archived-table', ['clientes' => $clientes]);
    }
}
