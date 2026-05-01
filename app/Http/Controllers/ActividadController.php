<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        // 🔥 Solo actividades del usuario logueado
        $actividades = Actividad::where('user_id', auth()->id())
            ->with(['user', 'logueable'])
            ->latest()
            ->paginate(20);

        return view('actividades.index', compact('actividades'));
    }

    /**
     * Elimina SOLO el historial del usuario actual
     */
    public function vaciar()
    {
        // 🔥 No borrar todo el sistema
        Actividad::where('user_id', auth()->id())->delete();

        return redirect()->route('actividades.index')
            ->with('success', 'Tu historial de actividad ha sido eliminado.');
    }
}
