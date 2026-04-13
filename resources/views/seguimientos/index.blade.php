<x-layouts::app :title="__('Tareas del Caso')">

    <div class="space-y-6">

        {{-- CABECERA --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            <h1 class="text-2xl font-bold italic font-sans">Listado de Tareas del Caso</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 font-sans">
                Gestiona y filtra las tareas, recordatorios y estados vinculados a tus clientes.
            </p>
        </div>

        @if(session('success'))
            <x-alert-success>
                {{ session('success') }}
            </x-alert-success>
        @endif

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            {{-- MOTOR DE FILTRADO COMBINADO --}}
            <form method="GET" action="{{ route('seguimientos.index') }}" class="mb-8 grid grid-cols-1 gap-3 w-full">
                
                {{-- Filtro Cliente --}}
                <div>
                    <select name="cliente_id" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Todos los Clientes</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ ($filtros['cliente_id'] ?? '') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro Estado --}}
                <div>
                    <select name="estado" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Cualquier Estado</option>
                        <option value="pendiente" {{ ($filtros['estado'] ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_curso" {{ ($filtros['estado'] ?? '') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                        <option value="resuelto" {{ ($filtros['estado'] ?? '') == 'resuelto' ? 'selected' : '' }}>Completada</option>
                    </select>
                </div>

                {{-- Filtro Prioridad --}}
                <div>
                    <select name="prioridad" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Cualquier Prioridad</option>
                        <option value="baja" {{ ($filtros['prioridad'] ?? '') == 'baja' ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ ($filtros['prioridad'] ?? '') == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta" {{ ($filtros['prioridad'] ?? '') == 'alta' ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>

                {{-- Filtro Etiqueta --}}
                <div>
                    <select name="etiqueta_id" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Todas las Etiquetas</option>
                        @foreach($etiquetas as $etiqueta)
                            <option value="{{ $etiqueta->id }}" {{ ($filtros['etiqueta_id'] ?? '') == $etiqueta->id ? 'selected' : '' }}>
                                {{ $etiqueta->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro Expediente --}}
                <div>
                    <select name="expediente_id" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Todos los Expedientes</option>
                        @foreach($expedientes as $expediente)
                            <option value="{{ $expediente->id }}" {{ ($filtros['expediente_id'] ?? '') == $expediente->id ? 'selected' : '' }}>
                                {{ $expediente->caratula }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones de acción --}}
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-bold hover:bg-blue-700 transition shadow-sm cursor-pointer font-sans">
                        🔍 Filtrar
                    </button>
                    <a href="{{ route('seguimientos.index') }}" class="bg-neutral-200 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 rounded-lg px-3 py-2 text-sm hover:bg-neutral-300 dark:hover:bg-neutral-700 transition flex items-center justify-center shadow-sm">
                        🧹
                    </a>
                </div>
            </form>

            @if($seguimientos->isEmpty())
                <div class="text-center py-10">
                    <p class="text-neutral-500 italic font-sans text-sm">No se encontraron tareas del caso con esos filtros.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700 text-neutral-400 text-[11px] uppercase tracking-wider font-black font-sans">
                                <th class="p-3 text-left">Cliente</th>
                                <th class="p-3 text-left">Expediente</th>
                                <th class="p-3 text-left">Teléfono</th>
                                <th class="p-3 text-left">Tarea</th>
                                <th class="p-3 text-left">Estado</th>
                                <th class="p-3 text-left">Vencimiento</th>
                                <th class="p-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                            @foreach($seguimientos as $seg)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40 transition">
                                    <td class="p-3 text-sm font-bold text-neutral-700 dark:text-neutral-200 font-sans whitespace-nowrap">
                                        {{ $seg->cliente->nombre }}
                                    </td>

                                    <td class="p-3 text-sm font-sans whitespace-nowrap">
    @if($seg->expediente)
        <span
            title="{{ $seg->expediente->titulo }}"
            class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 whitespace-nowrap"
        >
            📁 {{ $seg->expediente->titulo }}
        </span>
    @else
        <span class="text-neutral-400 italic text-xs whitespace-nowrap">Sin expediente</span>
    @endif
</td>

                                    <td class="p-3 text-sm font-sans">
                                        @if($seg->cliente->telefono)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $seg->cliente->telefono) }}" 
                                               target="_blank" 
                                               class="text-neutral-500 dark:text-neutral-400 hover:text-green-500 dark:hover:text-green-400 transition flex items-center gap-1"
                                               title="Enviar WhatsApp">
                                                <span class="text-xs">📞</span> {{ $seg->cliente->telefono }}
                                            </a>
                                        @else
                                            <span class="text-neutral-400 italic text-xs">Sin tel.</span>
                                        @endif
                                    </td>

                                    <td class="p-3 text-sm text-neutral-500 dark:text-neutral-400 max-w-[220px] truncate italic font-sans">
                                        "{{ $seg->descripcion }}"
                                    </td>

                                    <td class="p-3 text-sm">
                                        @php
                                            $styleEstado = match($seg->estado) {
                                                'en_curso'  => 'bg-blue-500 text-white',
                                                'pendiente' => 'bg-amber-500 text-white',
                                                'resuelto'  => 'bg-green-600 text-white',
                                                default     => 'bg-neutral-500 text-white',
                                            };

                                            $textoEstado = match($seg->estado) {
                                                'en_curso'  => 'En curso',
                                                'pendiente' => 'Pendiente',
                                                'resuelto'  => 'Completada',
                                                default     => str_replace('_', ' ', $seg->estado),
                                            };
                                        @endphp

                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase shadow-sm {{ $styleEstado }} font-sans">
                                            {{ $textoEstado }}
                                        </span>
                                    </td>

                                    <td class="p-3">
                                        @if($seg->etiqueta_vencimiento)
                                            <span class="px-2 py-0.5 rounded text-white text-[9px] font-black uppercase shadow-sm {{ $seg->color_vencimiento }} font-sans">
                                                {{ $seg->etiqueta_vencimiento }}
                                            </span>
                                        @else
                                            <span class="text-neutral-400 text-[10px] italic font-sans">-</span>
                                        @endif
                                    </td>

                                    <td class="p-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('clientes.show', $seg->cliente_id) }}" 
                                               class="p-2 rounded-lg text-neutral-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition cursor-pointer" 
                                               title="Ver Cliente">
                                                👁️
                                            </a>

                                            <a href="{{ route('seguimientos.edit', $seg->id) }}" 
                                               class="p-2 rounded-lg text-neutral-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition cursor-pointer" 
                                               title="Editar tarea del caso">
                                                ✏️
                                            </a>

                                            <form action="{{ route('seguimientos.destroy', $seg->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('¿Seguro que quieres eliminar esta tarea del caso?')" 
                                                        class="p-2 rounded-lg text-neutral-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition cursor-pointer" 
                                                        title="Eliminar tarea del caso">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 border-t border-neutral-100 dark:border-neutral-800 pt-4">
                    {{ $seguimientos->links() }}
                </div>
            @endif

        </div>
    </div>
</x-layouts::app>