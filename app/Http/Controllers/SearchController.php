<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Nota;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Capturamos lo que el usuario escribió en el buscador
        $query = $request->input('query');

        // Si el buscador está vacío, volvemos a la página anterior
        if (!$query) {
            return back();
        }

        // 2. Buscamos en Clientes (solo columnas existentes en tu DB)
        $clientes = Cliente::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('telefono', 'LIKE', "%{$query}%")
            ->get();

        // 3. Buscamos en Notas (Usamos 'contenido' que es el nombre real de tu columna)
        $notas = Nota::where('contenido', 'LIKE', "%{$query}%")
            ->with('cliente')
            ->get();

        // 4. Enviamos los resultados a la vista
        return view('search.results', compact('clientes', 'notas', 'query'));
    }
}
