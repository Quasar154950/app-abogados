<?php

namespace App\Livewire\Actions;

use App\Models\Seguimiento;
use Carbon\Carbon;
use Livewire\Component;

class DashboardSeguimientos extends Component
{
    public int $totalClientes = 0;
    public int $soloHoyNotas = 0;

    public int $cantHoy = 0;
    public int $cantVencidos = 0;
    public int $porcentaje = 100;
    public int $porcentajeRendimiento = 0;
    public int $resueltosHoy = 0;

    public bool $sinActividadHoy = true;

    public $vencidos;
    public $paraHoy;

    public ?int $procesandoId = null;

    public function mount($totalClientes = 0, $soloHoyNotas = 0): void
    {
        $this->totalClientes = (int) $totalClientes;
        $this->soloHoyNotas = (int) $soloHoyNotas;

        $this->cargarDatos();
    }

    public function cargarDatos(): void
    {
        $hoy = Carbon::today();

        $this->vencidos = Seguimiento::with('cliente')
            ->where('estado', '!=', 'resuelto')
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', '<', $hoy)
            ->orderBy('fecha_recordatorio')
            ->get();

        $this->paraHoy = Seguimiento::with('cliente')
            ->where('estado', '!=', 'resuelto')
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', $hoy)
            ->orderBy('created_at')
            ->get();

        $this->cantVencidos = $this->vencidos->count();
        $this->cantHoy = $this->paraHoy->count();

        $activos = Seguimiento::where('estado', '!=', 'resuelto')->count();
        $enTermino = max($activos - $this->cantVencidos, 0);

        $this->porcentaje = $activos > 0
            ? (int) round(($enTermino / $activos) * 100)
            : 100;

        $this->resueltosHoy = Seguimiento::where('estado', 'resuelto')
            ->whereDate('updated_at', $hoy)
            ->count();

        $actividadPendienteHoy = $this->cantHoy + $this->cantVencidos;
        $actividadTotalHoy = $this->resueltosHoy + $actividadPendienteHoy;

        $this->sinActividadHoy = $actividadTotalHoy === 0;

        $this->porcentajeRendimiento = $actividadTotalHoy > 0
            ? (int) round(($this->resueltosHoy / $actividadTotalHoy) * 100)
            : 0;
    }

    public function marcarComoResuelto(int $seguimientoId): void
    {
        $this->procesandoId = $seguimientoId;

        $seguimiento = Seguimiento::find($seguimientoId);

        if (!$seguimiento) {
            $this->procesandoId = null;
            return;
        }

        $seguimiento->update([
            'estado' => 'resuelto',
        ]);

        $this->cargarDatos();

        $this->procesandoId = null;
    }

    public function render()
    {
        return view('livewire.actions.dashboard-seguimientos');
    }
}
