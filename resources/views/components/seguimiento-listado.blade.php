<?php

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Expediente;
use App\Models\Seguimiento;
use Carbon\Carbon;

new class extends Component
{
    public ?int $clienteId = null;
    public ?int $expedienteId = null;

    public $estadoFiltro = null;

    protected $listeners = [
        'seguimiento-creado' => '$refresh',
        'filtrar-estado' => 'setFiltro'
    ];

    public function mount(?Cliente $cliente = null, ?Expediente $expediente = null)
    {
        $this->clienteId = $cliente?->id;
        $this->expedienteId = $expediente?->id;
        $this->estadoFiltro = request()->query('estado');
    }

    public function setFiltro($estado)
    {
        $this->estadoFiltro = is_array($estado) ? ($estado['estado'] ?? null) : $estado;
    }

    public function cambiarEstado($id, $nuevoEstado)
    {
        Seguimiento::where('id', $id)->update(['estado' => $nuevoEstado]);
    }

    public function borrar($id)
    {
        Seguimiento::destroy($id);
    }

    public function with(): array
    {
        if ($this->expedienteId) {
            $query = Seguimiento::where('expediente_id', $this->expedienteId)
                ->with(['etiqueta'])
                ->latest();
        } elseif ($this->clienteId) {
            $query = Seguimiento::where('cliente_id', $this->clienteId)
                ->whereNull('expediente_id')
                ->with(['etiqueta'])
                ->latest();
        } else {
            $query = Seguimiento::query()->whereNull('id');
        }

        if ($this->estadoFiltro) {
            $query->where('estado', $this->estadoFiltro);
        }

        return [
            'seguimientos' => $query->get(),
        ];
    }
};
?>

@php
    $esExpediente = !is_null($expedienteId);
    $tituloListado = $esExpediente ? '📋 Tareas del expediente' : '📋 Tareas del cliente';
    $textoVacio = $esExpediente
        ? 'No hay tareas del expediente registradas con este filtro.'
        : 'No hay tareas del cliente registradas con este filtro.';
    $titleEditar = $esExpediente ? 'Editar tarea del expediente' : 'Editar tarea del cliente';
    $titleEliminar = $esExpediente ? 'Eliminar tarea del expediente' : 'Eliminar tarea del cliente';
    $confirmEliminar = $esExpediente
        ? '¿Estás seguro de que deseas eliminar esta tarea del expediente?'
        : '¿Estás seguro de que deseas eliminar esta tarea del cliente?';
@endphp

<div>
    <div class="space-y-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold flex items-center gap-2 text-left text-neutral-400">
                {{ $tituloListado }}
            </h2>

            <span wire:loading class="text-[10px] font-bold uppercase text-green-600 animate-pulse">
                Actualizando lista...
            </span>
        </div>

        <div wire:loading.class="opacity-50" class="transition-opacity duration-200">
            @if($seguimientos->isEmpty())
                <div class="p-8 border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-xl">
                    <p class="text-sm text-neutral-500 italic text-center font-normal">
                        {{ $textoVacio }}
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($seguimientos as $seguimiento)
                        <div wire:key="seg-{{ $seguimiento->id }}" class="border rounded-xl p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800/40 transition shadow-sm bg-white dark:bg-neutral-900">

                            <div class="flex-1 w-full text-left font-normal">
                                <div class="flex gap-2 items-center mb-2 flex-wrap">
                                    @php
                                        $textoEstado = match($seguimiento->estado) {
                                            'pendiente' => 'Pendiente',
                                            'en_curso' => 'En curso',
                                            'resuelto' => 'Completada',
                                            default => ucfirst(str_replace('_', ' ', $seguimiento->estado)),
                                        };
                                    @endphp

                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded text-white shadow-sm {{ $seguimiento->estado === 'resuelto' ? 'bg-green-600' : ($seguimiento->estado === 'en_curso' ? 'bg-blue-600' : 'bg-yellow-500') }}">
                                        {{ $textoEstado }}
                                    </span>

                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded border {{ $seguimiento->prioridad === 'alta' ? 'border-red-500 text-red-600' : ($seguimiento->prioridad === 'media' ? 'border-blue-500 text-blue-600' : 'border-neutral-400 text-neutral-500') }}">
                                        {{ $seguimiento->prioridad }}
                                    </span>

                                    @if($seguimiento->fecha_recordatorio && $seguimiento->estado !== 'resuelto')
                                        @php
                                            $hoy = \Carbon\Carbon::today();
                                            $fecha = \Carbon\Carbon::parse($seguimiento->fecha_recordatorio);
                                        @endphp

                                        @if($fecha->isPast() && !$fecha->isToday())
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase text-white shadow-sm bg-red-600 animate-pulse">
                                                ⚠️ Vencida
                                            </span>
                                        @elseif($fecha->isToday())
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase text-white shadow-sm bg-orange-500">
                                                📅 Hoy
                                            </span>
                                        @elseif($fecha->diffInDays($hoy) <= 3)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase text-yellow-950 shadow-sm bg-yellow-400">
                                                ⏳ Próxima
                                            </span>
                                        @endif
                                    @endif

                                    @if($seguimiento->etiqueta)
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded text-white shadow-sm" style="background-color: {{ $seguimiento->etiqueta->color }};">
                                            {{ $seguimiento->etiqueta->nombre }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-neutral-800 dark:text-neutral-200 leading-relaxed">
                                    {{ $seguimiento->descripcion }}
                                </p>

                                @if($seguimiento->fecha_recordatorio)
                                    <p class="text-[10px] text-neutral-400 mt-2 font-bold uppercase tracking-tighter">
                                        📅 Fecha límite: {{ \Carbon\Carbon::parse($seguimiento->fecha_recordatorio)->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-row items-center gap-3 shrink-0 w-full md:w-auto justify-end border-t md:border-t-0 pt-3 md:pt-0 border-neutral-100 dark:border-neutral-800 font-bold">
                                <div class="flex gap-1">
                                    @foreach([
                                        'pendiente' => ['emoji' => '🟡', 'label' => 'Marcar como Pendiente'],
                                        'en_curso' => ['emoji' => '🔵', 'label' => 'Marcar En Curso'],
                                        'resuelto' => ['emoji' => '🟢', 'label' => 'Marcar como Completada']
                                    ] as $est => $data)
                                        <button wire:click="cambiarEstado({{ $seguimiento->id }}, '{{ $est }}')"
                                                title="{{ $data['label'] }}"
                                                class="w-9 h-9 inline-flex items-center justify-center rounded-lg transition-all cursor-pointer active:scale-90 {{ $seguimiento->estado === $est ? 'scale-110 opacity-100 shadow-sm border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800' : 'opacity-20 hover:opacity-100' }}">
                                            <span class="text-xl">{{ $data['emoji'] }}</span>
                                        </button>
                                    @endforeach
                                </div>

                                <div class="flex gap-1 border-l pl-2 border-neutral-200 dark:border-neutral-700">
                                    <a href="{{ route('seguimientos.edit', $seguimiento->id) }}"
                                       class="p-2 text-yellow-600 hover:scale-125 transition cursor-pointer"
                                       title="{{ $titleEditar }}">
                                        ✏️
                                    </a>

                                    <button wire:click="borrar({{ $seguimiento->id }})"
                                            wire:confirm="{{ $confirmEliminar }}"
                                            class="p-2 text-red-600 hover:scale-125 transition cursor-pointer"
                                            title="{{ $titleEliminar }}">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>