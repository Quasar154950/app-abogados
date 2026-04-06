<?php

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Expediente;
use App\Models\Seguimiento;
use App\Models\Etiqueta;

new class extends Component
{
    public Cliente $cliente;
    public ?Expediente $expediente = null;
    
    public $descripcion = '';
    public $estado = 'pendiente';
    public $prioridad = 'media';
    public $etiqueta_id = null;
    public $fecha_recordatorio = null;

    public function mount(Cliente $cliente, $expediente = null)
    {
        $this->cliente = $cliente;
        $this->expediente = $expediente;
    }

    public function guardarSeguimiento()
    {
        $this->validate([
            'descripcion' => 'required|string',
            'estado' => 'required|in:pendiente,en_curso,resuelto',
            'prioridad' => 'required|in:baja,media,alta',
            'etiqueta_id' => 'nullable|exists:etiquetas,id',
            'fecha_recordatorio' => 'nullable|date',
        ]);

        Seguimiento::create([
            'cliente_id' => $this->cliente->id,
            'expediente_id' => $this->expediente?->id,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad,
            'etiqueta_id' => $this->etiqueta_id,
            'fecha_recordatorio' => $this->fecha_recordatorio ?: null,
        ]);

        $this->reset([
            'descripcion',
            'estado',
            'prioridad',
            'etiqueta_id',
            'fecha_recordatorio'
        ]);

        $this->estado = 'pendiente';
        $this->prioridad = 'media';

        $this->dispatch('seguimiento-creado');

        session()->flash('success', 'Tarea agregada correctamente');
    }

    public function with(): array
    {
        return [
            'etiquetas' => Etiqueta::orderBy('nombre')->get(),
        ];
    }
};
?>

<div>
    <h2 class="text-xl font-bold mb-4 text-left dark:text-neutral-300">
        {{ $expediente ? 'Agregar Tarea al Expediente' : 'Agregar Tarea del Cliente' }}
    </h2>
    
    <div class="space-y-4">

        {{-- DESCRIPCIÓN --}}
        <textarea wire:model="descripcion"
            rows="3"
            placeholder="Detalle de la tarea, gestión o recordatorio..."
            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm font-normal dark:bg-neutral-800 dark:border-neutral-700 outline-none focus:ring-2 focus:ring-green-500 text-neutral-800 dark:text-neutral-200">
        </textarea>

        {{-- GRID --}}
        <div class="grid grid-cols-2 gap-4 text-left">

            {{-- Estado --}}
            <div>
                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Estado</label>
                <select wire:model="estado"
                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="pendiente">Pendiente</option>
                    <option value="en_curso">En curso</option>
                    <option value="resuelto">Completada</option>
                </select>
            </div>

            {{-- Prioridad --}}
            <div>
                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Prioridad</label>
                <select wire:model="prioridad"
                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 cursor-pointer">
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
            </div>

            {{-- Etiqueta --}}
            <div>
                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Etiqueta</label>
                <select wire:model="etiqueta_id"
                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 font-bold cursor-pointer">
                    <option value="">Sin etiqueta</option>
                    @foreach($etiquetas as $et)
                        <option value="{{ $et->id }}">
                            {{ $et->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Fecha --}}
            <div>
                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Fecha límite</label>
                <input type="date"
                    wire:model="fecha_recordatorio"
                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 cursor-pointer">
            </div>
        </div>

        {{-- BOTÓN --}}
        <button type="button"
            wire:click="guardarSeguimiento"
            wire:loading.attr="disabled"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-10 py-3 text-sm font-bold text-white hover:bg-green-700 transition w-full md:w-auto shadow-md cursor-pointer active:scale-95">

            <span wire:loading.remove wire:target="guardarSeguimiento">
                ✔ Guardar tarea
            </span>

            <span wire:loading wire:target="guardarSeguimiento">
                ⏳ Guardando...
            </span>

        </button>
    </div>
</div>