<?php

use Livewire\Volt\Component; // Importante que sea Volt
use App\Models\Cliente;

new class extends Component
{
    // Definimos que este componente recibe un Cliente
    public Cliente $cliente;

    public function mount(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    // Esta función prepara los datos para la vista
    public function with(): array
    {
        return [
            'seguimientos' => $this->cliente->seguimientos()
                ->with('etiqueta')
                ->latest()
                ->get(),
        ];
    }
};
?>