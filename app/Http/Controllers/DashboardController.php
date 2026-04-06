<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Nota;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // --- 1. DATOS SUPERIORES ---

        $totalClientesActivos = Cliente::where('archivado', false)->count();

        $conteoTotalNotas = Nota::count();

        $conteoNotasDeHoy = Nota::whereDate('created_at', $hoy)->count();

        // Seguimientos pendientes / en curso para HOY
        $cantHoy = Seguimiento::whereIn('estado', ['pendiente', 'en_curso'])
            ->whereDate('fecha_recordatorio', $hoy)
            ->count();

        // Seguimientos vencidos
        $cantVencidos = Seguimiento::whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', '<', $hoy)
            ->count();

        // Seguimientos resueltos HOY
        $resueltosHoy = Seguimiento::where('estado', 'resuelto')
            ->whereDate('updated_at', $hoy)
            ->count();

        // --- 2. KPI 1: SALUD GENERAL ---
        // Qué porcentaje de seguimientos activos NO está vencido

        $totalActivos = Seguimiento::whereIn('estado', ['pendiente', 'en_curso'])->count();

        $porcentajeSalud = ($totalActivos > 0)
            ? round((($totalActivos - $cantVencidos) / $totalActivos) * 100)
            : 100;

        // --- 3. KPI 2: RENDIMIENTO DIARIO ---
        // Qué porcentaje de lo de HOY fue resuelto HOY

        $totalMetaHoy = $cantHoy + $resueltosHoy;

        if ($totalMetaHoy == 0) {
            $porcentajeRendimiento = 100;
            $sinActividadHoy = true;
        } else {
            $porcentajeRendimiento = round(($resueltosHoy / $totalMetaHoy) * 100);
            $sinActividadHoy = false;
        }

        // --- 4. LISTAS DE ACCESO RÁPIDO ---

        $notasFijadas = Nota::where('is_pinned', true)
            ->with('cliente')
            ->latest()
            ->take(5)
            ->get();

        $ultimosClientes = Cliente::where('archivado', false)
            ->latest()
            ->take(5)
            ->get();

        $vencidos = Seguimiento::whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', '<', $hoy)
            ->with('cliente')
            ->get();

        $paraHoy = Seguimiento::whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', $hoy)
            ->with('cliente')
            ->get();

        $proximosRecordatorios = Seguimiento::whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', '>', $hoy)
            ->orderBy('fecha_recordatorio', 'asc')
            ->take(5)
            ->with('cliente')
            ->get();

        // --- 5. RETORNO ---

        return view('dashboard', [
            'totalClientes'           => $totalClientesActivos,
            'historicoNotas'          => $conteoTotalNotas,
            'soloHoyNotas'            => $conteoNotasDeHoy,
            'cantHoy'                 => $cantHoy,
            'cantVencidos'            => $cantVencidos,
            'resueltosHoy'            => $resueltosHoy,
            'porcentaje'              => $porcentajeSalud,
            'porcentajeRendimiento'   => $porcentajeRendimiento,
            'sinActividadHoy'         => $sinActividadHoy,
            'ultimosClientes'         => $ultimosClientes,
            'notasFijadas'            => $notasFijadas,
            'vencidos'                => $vencidos,
            'paraHoy'                 => $paraHoy,
            'proximosRecordatorios'   => $proximosRecordatorios,
        ]);
    }
}