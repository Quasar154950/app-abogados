<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use App\Models\User; // 🔥 agregado
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
    use WithPagination;

    public $busqueda = '';

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function archivar($id)
    {
        $cliente = Cliente::where('abogado_id', auth()->id())->find($id);

        if ($cliente) {
            $cliente->update(['archivado' => true]);
            session()->flash('success', 'Cliente archivado correctamente.');
        }
    }

    public function delete($id)
    {
        $cliente = Cliente::where('abogado_id', auth()->id())->find($id);

        if ($cliente) {
            $userId = $cliente->user_id;

            $cliente->delete();

            if ($userId) {
                User::where('id', $userId)->delete();
            }

            session()->flash('success', 'Cliente y acceso eliminados definitivamente.');
        }
    }

    public function render()
    {
        $clientes = Cliente::query()
            ->where('abogado_id', auth()->id())
            ->where('archivado', false)
            ->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->busqueda . '%')
                    ->orWhere('email', 'like', '%' . $this->busqueda . '%')
                    ->orWhere('telefono', 'like', '%' . $this->busqueda . '%');
            })
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('livewire.clientes.index-table', ['clientes' => $clientes]);
    }
}
