<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Nota;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return back();
        }

        $abogadoId = auth()->id();

        $clientes = Cliente::where('abogado_id', $abogadoId)
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('telefono', 'LIKE', "%{$query}%");
            })
            ->get();

        $notas = Nota::whereHas('cliente', function ($q) use ($abogadoId) {
                $q->where('abogado_id', $abogadoId);
            })
            ->where('contenido', 'LIKE', "%{$query}%")
            ->with('cliente')
            ->get();

        return view('search.results', compact(
            'clientes',
            'notas',
            'query'
        ));
    }
}
