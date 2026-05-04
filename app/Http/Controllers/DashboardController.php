<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Nota;
use App\Models\Seguimiento;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔴 REDIRIGIR SOPORTE AL PANEL DE SOPORTE
        if (auth()->user()->email === 'soporte@tuempresa.com') {
            return redirect('/soporte');
        }

        $hoy = Carbon::today();
        $abogadoId = auth()->id();

        $usuario = auth()->user();

        $diasRestantes = $usuario->fecha_vencimiento
            ? max(0, now()->startOfDay()->diffInDays($usuario->fecha_vencimiento->startOfDay(), false))
            : null;

        $totalClientesActivos = Cliente::where('abogado_id', $abogadoId)
            ->where('archivado', false)
            ->count();

        $conteoTotalNotas = Nota::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })->count();

        $conteoNotasDeHoy = Nota::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })->whereDate('created_at', $hoy)->count();

        $cantHoy = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->whereDate('fecha_recordatorio', $hoy)
            ->count();

        $cantVencidos = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', '<', $hoy)
            ->count();

        $resueltosHoy = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->where('estado', 'resuelto')
            ->whereDate('updated_at', $hoy)
            ->count();

        $totalActivos = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->count();

        $porcentajeSalud = ($totalActivos > 0)
            ? round((($totalActivos - $cantVencidos) / $totalActivos) * 100)
            : 100;

        $totalMetaHoy = $cantHoy + $resueltosHoy;

        if ($totalMetaHoy == 0) {
            $porcentajeRendimiento = 100;
            $sinActividadHoy = true;
        } else {
            $porcentajeRendimiento = round(($resueltosHoy / $totalMetaHoy) * 100);
            $sinActividadHoy = false;
        }

        $notasFijadas = Nota::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->where('is_pinned', true)
            ->with('cliente')
            ->latest()
            ->take(5)
            ->get();

        $ultimosClientes = Cliente::where('abogado_id', $abogadoId)
            ->where('archivado', false)
            ->latest()
            ->take(5)
            ->get();

        $vencidos = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', '<', $hoy)
            ->with('cliente')
            ->get();

        $paraHoy = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', $hoy)
            ->with('cliente')
            ->get();

        $proximosRecordatorios = Seguimiento::whereHas('cliente', function ($q) use ($abogadoId) {
            $q->where('abogado_id', $abogadoId);
        })
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->whereNotNull('fecha_recordatorio')
            ->whereDate('fecha_recordatorio', '>', $hoy)
            ->orderBy('fecha_recordatorio', 'asc')
            ->take(5)
            ->with('cliente')
            ->get();

        return view('dashboard', [
            'diasRestantes'           => $diasRestantes,
            
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