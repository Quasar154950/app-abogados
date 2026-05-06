<div>
    @if($seguimientos->isEmpty())
        <div class="text-center py-10">
            <p class="text-neutral-500 italic font-sans text-sm">
                No se encontraron tareas del caso con esos filtros.
            </p>
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
                                        title="Tarea del expediente: {{ $seg->expediente->caratula }}"
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 whitespace-nowrap"
                                    >
                                        📁 {{ $seg->expediente->caratula }}
                                    </span>
                                @else
                                    <span class="text-neutral-400 italic text-xs whitespace-nowrap">
                                        Tarea del cliente
                                    </span>
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
                                       title="{{ $seg->expediente ? 'Editar tarea del expediente' : 'Editar tarea del cliente' }}">
                                        ✏️
                                    </a>

                                    <button
                                        type="button"
                                        onclick="confirm('¿Seguro que quieres eliminar esta tarea del caso?') || event.stopImmediatePropagation()"
                                        wire:click="eliminar({{ $seg->id }})"
                                        class="p-2 rounded-lg text-neutral-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition cursor-pointer"
                                        title="Eliminar tarea del caso">
                                        🗑️
                                    </button>
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