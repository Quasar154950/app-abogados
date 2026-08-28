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
    public $hora_recordatorio = null;
    public $visible_para_cliente = false;
    public $tipo_registro = 'movimiento';

    public function mount(Cliente $cliente, $expediente = null)
    {
        $this->cliente = $cliente;
        $this->expediente = $expediente;
    }

    public function seleccionarTipo($tipo)
    {
        $this->tipo_registro = $tipo;

        if ($tipo === 'movimiento') {
            $this->fecha_recordatorio = null;
            $this->hora_recordatorio = null;
        }
    }

    public function guardarSeguimiento()
    {
        $reglas = [
            'descripcion' => 'required|string',
            'estado' => 'required|in:pendiente,en_curso,resuelto',
            'prioridad' => 'required|in:baja,media,alta',
            'etiqueta_id' => 'nullable|exists:etiquetas,id',
            'visible_para_cliente' => 'boolean',
            'tipo_registro' => 'required|in:movimiento,fecha_importante',
        ];

        if ($this->tipo_registro === 'fecha_importante') {
            $reglas['fecha_recordatorio'] = 'required|date';
            $reglas['hora_recordatorio'] = 'nullable|date_format:H:i';
        } else {
            $reglas['fecha_recordatorio'] = 'nullable|date';
            $reglas['hora_recordatorio'] = 'nullable|date_format:H:i';
        }

        $this->validate($reglas);

        $tipoGuardado = $this->tipo_registro;

        Seguimiento::create([
            'cliente_id' => $this->cliente->id,
            'expediente_id' => $this->expediente?->id,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad,
            'etiqueta_id' => $this->etiqueta_id,
            'fecha_recordatorio' => $this->tipo_registro === 'fecha_importante'
                ? $this->fecha_recordatorio
                : null,
            'hora_recordatorio' => $this->tipo_registro === 'fecha_importante'
                ? ($this->hora_recordatorio ?: null)
                : null,
            'visible_para_cliente' => $this->visible_para_cliente,
        ]);

        $this->reset([
            'descripcion',
            'estado',
            'prioridad',
            'etiqueta_id',
            'fecha_recordatorio',
            'hora_recordatorio',
            'visible_para_cliente',
            'tipo_registro',
        ]);

        $this->estado = 'pendiente';
        $this->prioridad = 'media';
        $this->tipo_registro = 'movimiento';

        $this->dispatch('seguimiento-creado');

        session()->flash(
            'success',
            $tipoGuardado === 'fecha_importante'
                ? 'Fecha importante agregada correctamente'
                : 'Movimiento agregado correctamente'
        );
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
        {{ $expediente ? 'Agregar actualización al expediente' : 'Agregar actualización del cliente' }}
    </h2>

    <div class="space-y-4">

        {{-- TIPO DE REGISTRO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            <button
                type="button"
                wire:click="seleccionarTipo('movimiento')"
                class="rounded-xl border p-4 text-left transition cursor-pointer
                    {{ $tipo_registro === 'movimiento'
                        ? 'border-green-500 bg-green-50 dark:bg-green-950/20'
                        : 'border-neutral-200 dark:border-neutral-700 hover:border-neutral-400' }}"
            >
                <div class="flex items-start gap-3">
                    <div class="text-2xl">📝</div>

                    <div>
                        <div class="font-bold text-neutral-800 dark:text-neutral-200">
                            Registrar movimiento
                        </div>

                        <div class="mt-1 text-xs text-neutral-500">
                            Para informar algo que ya ocurrió en el expediente.
                        </div>

                        <div class="mt-2 text-xs text-neutral-400">
                            Ejemplo: Se presentó documentación.
                        </div>
                    </div>
                </div>
            </button>

            <button
                type="button"
                wire:click="seleccionarTipo('fecha_importante')"
                class="rounded-xl border p-4 text-left transition cursor-pointer
                    {{ $tipo_registro === 'fecha_importante'
                        ? 'border-green-500 bg-green-50 dark:bg-green-950/20'
                        : 'border-neutral-200 dark:border-neutral-700 hover:border-neutral-400' }}"
            >
                <div class="flex items-start gap-3">
                    <div class="text-2xl">📅</div>

                    <div>
                        <div class="font-bold text-neutral-800 dark:text-neutral-200">
                            Programar fecha importante
                        </div>

                        <div class="mt-1 text-xs text-neutral-500">
                            Para una audiencia, vencimiento, reunión o evento futuro.
                        </div>

                        <div class="mt-2 text-xs text-neutral-400">
                            Ejemplo: Audiencia de conciliación.
                        </div>
                    </div>
                </div>
            </button>

        </div>

        {{-- DESCRIPCIÓN --}}
        <div>
            <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                {{ $tipo_registro === 'fecha_importante' ? 'Descripción del evento' : 'Descripción del movimiento' }}
            </label>

            <textarea
                wire:model="descripcion"
                rows="3"
                placeholder="{{ $tipo_registro === 'fecha_importante'
                    ? 'Ej: Audiencia de conciliación...'
                    : 'Ej: Se presentó nueva documentación en el expediente...' }}"
                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm font-normal dark:bg-neutral-800 dark:border-neutral-700 outline-none focus:ring-2 focus:ring-green-500 text-neutral-800 dark:text-neutral-200"
            ></textarea>

            @error('descripcion')
                <div class="mt-1 text-xs text-red-500">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">

            {{-- Estado --}}
            <div>
                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                    Estado
                </label>

                <select
                    wire:model="estado"
                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 cursor-pointer"
                >
                    <option value="pendiente">Pendiente</option>
                    <option value="en_curso">En curso</option>
                    <option value="resuelto">Completada</option>
                </select>
            </div>

            {{-- Prioridad --}}
            <div>
                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                    Prioridad
                </label>

                <select
                    wire:model="prioridad"
                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 cursor-pointer"
                >
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
            </div>

            {{-- Etiqueta --}}
            <div>
                <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                    Etiqueta
                </label>

                <select
                    wire:model="etiqueta_id"
                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 font-bold cursor-pointer"
                >
                    <option value="">Sin etiqueta</option>

                    @foreach($etiquetas as $et)
                        <option value="{{ $et->id }}">
                            {{ $et->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- FECHA Y HORA SOLO PARA AGENDA --}}
            @if($tipo_registro === 'fecha_importante')

                <div>
                    <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                        Fecha importante
                    </label>

                    <input
                        type="date"
                        wire:model="fecha_recordatorio"
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 cursor-pointer"
                    >

                    @error('fecha_recordatorio')
                        <div class="mt-1 text-xs text-red-500">
                            La fecha es obligatoria para una fecha importante.
                        </div>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                        Hora
                    </label>

                    <input
                        type="time"
                        wire:model="hora_recordatorio"
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-800 dark:text-neutral-200 cursor-pointer"
                    >
                </div>

            @endif

        </div>

        {{-- VISIBILIDAD PARA CLIENTE --}}
        <div class="flex items-start gap-3 rounded-xl border border-neutral-200 p-4 dark:border-neutral-700">

            <input
                type="checkbox"
                wire:model="visible_para_cliente"
                id="visible_para_cliente"
                class="mt-1 rounded border-neutral-300 text-green-600 focus:ring-green-500"
            >

            <label for="visible_para_cliente" class="cursor-pointer">

                <div class="text-sm font-bold text-neutral-800 dark:text-neutral-200">
                    Mostrar al cliente
                </div>

                <div class="text-xs text-neutral-500 mt-1">
                    @if($tipo_registro === 'fecha_importante')
                        Si lo activás, esta fecha podrá aparecer en la Agenda de la app del cliente.
                    @else
                        Si lo activás, este movimiento podrá aparecer en Últimas novedades de la app del cliente.
                    @endif
                </div>

            </label>

        </div>

        {{-- BOTÓN --}}
        <button
            type="button"
            wire:click="guardarSeguimiento"
            wire:loading.attr="disabled"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-10 py-3 text-sm font-bold text-white hover:bg-green-700 transition w-full md:w-auto shadow-md cursor-pointer active:scale-95"
        >

            <span wire:loading.remove wire:target="guardarSeguimiento">
                @if($tipo_registro === 'fecha_importante')
                    📅 Guardar fecha importante
                @else
                    ✔ Guardar movimiento
                @endif
            </span>

            <span wire:loading wire:target="guardarSeguimiento">
                ⏳ Guardando...
            </span>

        </button>

    </div>
</div>

