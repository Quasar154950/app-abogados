<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Cliente;
use App\Models\ReservaTurno;

class TurnoController extends Controller
{
    public function index()
    {
        $fechaSeleccionada = request('fecha', now()->toDateString());
        $fecha = \Carbon\Carbon::parse($fechaSeleccionada);
        $cerradoFinDeSemana = $fecha->isSaturday() || $fecha->isSunday();

        $turnos = Turno::where('activo', true)
            ->whereDate('fecha', $fechaSeleccionada)
            ->orderBy('hora_inicio')
            ->orderBy('actividad')
            ->get();

        if (auth()->user()->role === 'cliente') {
            return view('turnos.index', compact(
                'turnos',
                'fechaSeleccionada',
                'cerradoFinDeSemana'
            ));
        }

        return view('turnos.admin', compact(
            'turnos',
            'fechaSeleccionada',
            'cerradoFinDeSemana'
        ));
    }

    public function reservar(Turno $turno)
    {
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente) {
            return back()->with('error', 'No existe cliente asociado.');
        }

        $existe = ReservaTurno::where('cliente_id', $cliente->id)
            ->where('turno_id', $turno->id)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ya tienes reservado este turno.');
        }

        $reservados = $turno->reservas()->count();

        if ($reservados >= $turno->cupo_maximo) {
            return back()->with('error', 'El turno está completo.');
        }

        ReservaTurno::create([
            'cliente_id' => $cliente->id,
            'turno_id' => $turno->id,
            'estado' => 'reservado',
        ]);

        return back()->with('success', 'Turno reservado correctamente.');
    }

    public function cancelarReserva(ReservaTurno $reserva)
    {
        $cliente = Cliente::where('user_id', auth()->id())->first();

        if (!$cliente || $reserva->cliente_id !== $cliente->id) {
            abort(403);
        }

        $reserva->delete();

        return back()->with('success', 'Reserva cancelada correctamente.');
    }
}
