<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use App\Models\Nota;

class DashboardNotasFijadas extends Component
{
    public function togglePin($id)
    {
        $nota = Nota::whereHas('cliente', function ($q) {
            $q->where('abogado_id', auth()->id());
        })->find($id);

        if (!$nota) {
            return;
        }

        $nota->update([
            'is_pinned' => !$nota->is_pinned,
            'updated_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.actions.dashboard-notas-fijadas', [
            'notasFijadas' => Nota::whereHas('cliente', function ($q) {
                    $q->where('abogado_id', auth()->id());
                })
                ->where('is_pinned', true)
                ->with('cliente')
                ->latest('updated_at')
                ->take(5)
                ->get(),
        ]);
    }
}