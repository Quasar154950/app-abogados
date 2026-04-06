<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use App\Models\Nota;

class DashboardNotasFijadas extends Component
{
    // Quitar / poner pin desde el dashboard
    public function togglePin($id)
    {
        $nota = Nota::find($id);

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
            'notasFijadas' => Nota::where('is_pinned', true)
                ->with('cliente')
                ->latest('updated_at')
                ->take(5)
                ->get(),
        ]);
    }
}