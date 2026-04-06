<x-layouts::app :title="__('Ver Expediente')">

    <div class="space-y-6 pb-10 text-left">

        @if(session('success'))
            <x-alert-success>{{ session('success') }}</x-alert-success>
        @endif

        <x-alert-error />

        {{-- CABECERA DEL EXPEDIENTE --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100">
                            {{ $expediente->caratula }}
                        </h1>

                        @php
                            $estadoExpediente = match($expediente->estado) {
                                'iniciado' => 'bg-blue-600 text-white',
                                'en_tramite' => 'bg-yellow-500 text-white',
                                'finalizado' => 'bg-green-600 text-white',
                                'archivado' => 'bg-neutral-700 text-white',
                                default => 'bg-blue-600 text-white',
                            };

                            $textoEstadoExpediente = match($expediente->estado) {
                                'iniciado' => 'Iniciado',
                                'en_tramite' => 'En trámite',
                                'finalizado' => 'Finalizado',
                                'archivado' => 'Archivado',
                                default => ucfirst(str_replace('_', ' ', $expediente->estado)),
                            };
                        @endphp

                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase shadow-sm {{ $estadoExpediente }}">
                            {{ $textoEstadoExpediente }}
                        </span>
                    </div>

                    <p class="text-sm text-neutral-600 dark:text-neutral-400">
                        Información general del expediente y seguimiento de tareas vinculadas.
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('expedientes.edit', $expediente->id) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-bold text-white hover:bg-yellow-600 transition">
                        ✏️ Editar
                    </a>

                    <a href="{{ route('clientes.show', $expediente->cliente_id) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-neutral-200 px-4 py-2 text-sm font-bold">
                        ← Volver
                    </a>
                </div>
            </div>
        </div>

        {{-- DATOS DEL EXPEDIENTE --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <div>📁 Número: {{ $expediente->numero_expediente ?: 'Sin cargar' }}</div>
                <div>⚖️ Juzgado: {{ $expediente->juzgado ?: 'Sin cargar' }}</div>
                <div>🏛️ Tipo: {{ $expediente->tipo ?: 'Sin definir' }}</div>
                <div>📅 Inicio: {{ $expediente->fecha_inicio ?: 'Sin fecha' }}</div>
                <div>🕒 Creado: {{ $expediente->created_at->format('d/m/Y') }}</div>
            </div>

            @if($expediente->observaciones)
                <p class="mt-3 text-sm italic">
                    "{{ $expediente->observaciones }}"
                </p>
            @endif

        </div>

        {{-- LISTADO DE TAREAS DEL EXPEDIENTE --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            <div class="mb-4">
                <h2 class="text-xl font-bold">Tareas del expediente</h2>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Gestión de tareas vinculadas exclusivamente a este expediente.
                </p>
            </div>

            {{-- Filtros Reactivos --}}
            <div class="flex gap-2 flex-wrap mb-6 border-b border-neutral-100 dark:border-neutral-800 pb-4">

                <button
                    x-on:click="$dispatch('filtrar-estado', { estado: '' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-neutral-200 dark:bg-neutral-800 px-3 py-1.5 text-sm font-bold cursor-pointer active:scale-95 transition hover:bg-neutral-300 dark:hover:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    🗂️ Todas
                </button>

                <button
                    x-on:click="$dispatch('filtrar-estado', { estado: 'pendiente' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-3 py-1.5 text-sm text-white font-bold cursor-pointer active:scale-95 transition hover:bg-yellow-600">
                    🟡 Pendientes
                </button>

                <button
                    x-on:click="$dispatch('filtrar-estado', { estado: 'en_curso' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm text-white font-bold cursor-pointer active:scale-95 transition hover:bg-blue-700">
                    🔵 En curso
                </button>

                <button
                    x-on:click="$dispatch('filtrar-estado', { estado: 'resuelto' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 py-1.5 text-sm text-white font-bold cursor-pointer active:scale-95 transition hover:bg-green-700">
                    🟢 Completadas
                </button>
            </div>

            @livewire('seguimiento-listado', ['expediente' => $expediente], key('seguimiento-listado-expediente-' . $expediente->id))

        </div>

        {{-- NUEVA TAREA DEL EXPEDIENTE --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            @livewire('seguimiento-formulario', [
                'cliente' => $expediente->cliente,
                'expediente' => $expediente
            ], key('seguimiento-formulario-expediente-' . $expediente->id))
        </div>

    </div>

</x-layouts::app>