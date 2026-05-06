<?php

namespace App\Livewire;

use App\Models\Seguimiento;
use Livewire\Component;
use Livewire\WithPagination;

class SeguimientosTabla extends Component
{
    use WithPagination;

    public $cliente_id = '';
    public $expediente_id = '';
    public $estado = '';
    public $etiqueta_id = '';
    public $prioridad = '';
    public $vencimiento = '';

    protected $queryString = [
        'cliente_id' => ['except' => ''],
        'expediente_id' => ['except' => ''],
        'estado' => ['except' => ''],
        'etiqueta_id' => ['except' => ''],
        'prioridad' => ['except' => ''],
        'vencimiento' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->cliente_id = request('cliente_id', '');
        $this->expediente_id = request('expediente_id', '');
        $this->estado = request('estado', '');
        $this->etiqueta_id = request('etiqueta_id', '');
        $this->prioridad = request('prioridad', '');
        $this->vencimiento = request('vencimiento', '');
    }

    public function eliminar(int $id): void
    {
        $seguimiento = Seguimiento::whereHas('cliente', function ($q) {
                $q->where('abogado_id', auth()->id());
            })
            ->find($id);

        if (!$seguimiento) {
            return;
        }

        $seguimiento->delete();
    }

    public function render()
    {
        $abogadoId = auth()->id();

        $query = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
                $q->where('abogado_id', $abogadoId);
            })
            ->with(['cliente', 'etiqueta', 'expediente']);

        if ($this->cliente_id) {
            $query->where('cliente_id', $this->cliente_id);
        }

        if ($this->expediente_id) {
            $query->where('expediente_id', $this->expediente_id);
        }

        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        if ($this->etiqueta_id) {
            $query->where('etiqueta_id', $this->etiqueta_id);
        }

        if ($this->prioridad) {
            $query->where('prioridad', $this->prioridad);
        }

        if ($this->vencimiento === 'vencido') {
            $query->where('fecha_recordatorio', '<', now()->startOfDay())
                ->where('estado', '!=', 'resuelto');
        }

        if ($this->vencimiento === 'hoy') {
            $query->whereDate('fecha_recordatorio', now()->today());
        }

        return view('livewire.seguimientos-tabla', [
            'seguimientos' => $query->latest()->paginate(15),
        ]);
    }
}
