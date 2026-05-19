<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
    use WithPagination;

    public $busqueda = '';

    public $clientePagoId = null;
    public $montoPago = '';
    public $metodoPago = 'Efectivo';
    public $observacionPago = '';

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function abrirRenovacion($id)
    {
        $this->clientePagoId = $id;
        $this->montoPago = '';
        $this->metodoPago = 'Efectivo';
        $this->observacionPago = '';
    }

    public function cancelarRenovacion()
    {
        $this->clientePagoId = null;
        $this->montoPago = '';
        $this->metodoPago = 'Efectivo';
        $this->observacionPago = '';
    }

    public function archivar($id)
    {
        $cliente = Cliente::where('abogado_id', auth()->id())->find($id);

        if ($cliente) {
            $cliente->update(['archivado' => true]);

            session()->flash('success', 'Socio dado de baja correctamente.');
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

            session()->flash('success', 'Socio y acceso eliminados definitivamente.');
        }
    }

    public function renovarCuota()
    {
        $this->validate([
            'clientePagoId' => 'required|exists:clientes,id',
            'montoPago' => 'required|numeric|min:0',
            'metodoPago' => 'required|string|max:255',
            'observacionPago' => 'nullable|string|max:1000',
        ]);

        $cliente = Cliente::where('abogado_id', auth()->id())->find($this->clientePagoId);

        if ($cliente) {

            $nuevoVencimiento = now()->addDays(30);

            $cliente->fecha_vencimiento_cuota = $nuevoVencimiento;
            $cliente->save();

            Pago::create([
                'cliente_id' => $cliente->id,
                'monto' => $this->montoPago,
                'metodo_pago' => $this->metodoPago,
                'observacion' => $this->observacionPago ?: 'Renovación de cuota mensual',
                'fecha_pago' => now()->toDateString(),
                'vencimiento_cuota' => $nuevoVencimiento->toDateString(),
            ]);

            $this->cancelarRenovacion();

            session()->flash('success', 'Cuota renovada por 30 días y pago registrado correctamente.');
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
            ->withCount([
                'mensajes as mensajes_no_leidos_count' => function ($query) {
                    $query->where('remitente', 'cliente')
                        ->where('leido', false);
                }
            ])
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('livewire.clientes.index-table', [
            'clientes' => $clientes
        ]);
    }
}
