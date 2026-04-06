<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        // Traemos los logs, cargando la relación con el usuario y el objeto tocado (logueable)
        $actividades = Actividad::with(['user', 'logueable'])
            ->latest()
            ->paginate(20);

        return view('actividades.index', compact('actividades'));
    }

    /**
     * Elimina todos los registros del historial.
     */
    public function vaciar()
    {
        // El método truncate vacía la tabla y resetea los IDs a 1
        Actividad::truncate();

        return redirect()->route('actividades.index')
            ->with('success', 'El historial de actividad ha sido vaciado por completo.');
    }
}
