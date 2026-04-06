<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
    use WithPagination;

    public $busqueda = '';

    // Si el usuario escribe, volvemos a la página 1 de la tabla automáticamente
    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    /**
     * Archiva un cliente (cambia el estado a archivado = true)
     */
    public function archivar($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $cliente->update(['archivado' => true]);
            session()->flash('success', 'Cliente archivado correctamente.');
        }
    }

    /**
     * Elimina un cliente permanentemente de la base de datos
     */
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
            // Filtramos para mostrar solo los NO archivados
            ->where('archivado', false)
            ->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('email', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('telefono', 'like', '%' . $this->busqueda . '%');
            })
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('livewire.clientes.index-table', ['clientes' => $clientes]);
    }
}
