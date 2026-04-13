<x-layouts::app :title="__('Historial de Actividad')">

    <div class="space-y-6">

        {{-- CABECERA CON BOTÓN DE VACIAR --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold italic font-sans">Historial de Actividad</h1>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 font-sans">
                    Registro detallado de todos los cambios, creaciones y eliminaciones en el sistema.
                </p>
            </div>

            @if(!$actividades->isEmpty())
                <form action="{{ route('actividades.vaciar') }}" method="POST" onsubmit="return confirm('⚠️ ¿Estás seguro? Esta acción borrará TODO el historial permanentemente y no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 px-4 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-red-100 dark:hover:bg-red-900/40 transition cursor-pointer font-sans border border-red-200 dark:border-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Vaciar Historial
                    </button>
                </form>
            @endif
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            @if($actividades->isEmpty())
                <div class="text-center py-10">
                    <p class="text-neutral-500 italic font-sans text-sm">Aún no hay actividad registrada.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-neutral-200 dark:border-neutral-700 text-neutral-400 text-[11px] uppercase tracking-wider font-black font-sans">
                                <th class="p-3 text-left">Fecha</th>
                                <th class="p-3 text-left">Usuario</th>
                                <th class="p-3 text-left">Acción</th>
                                <th class="p-3 text-left">Elemento</th>
                                <th class="p-3 text-left">Detalle de Cambios</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                            @foreach($actividades as $act)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40 transition text-sm">

                                    {{-- Fecha --}}
                                    <td class="p-3 text-xs text-neutral-500 font-sans whitespace-nowrap">
                                        {{ $act->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    {{-- Usuario --}}
                                    <td class="p-3 font-bold text-neutral-700 dark:text-neutral-200 font-sans whitespace-nowrap">
                                        {{ $act->user->name ?? 'Sistema' }}
                                    </td>

                                    {{-- Acción --}}
                                    <td class="p-3 whitespace-nowrap">
                                        @php
                                            $colorAccion = match($act->accion) {
                                                'created' => 'bg-green-600',
                                                'updated' => 'bg-blue-500',
                                                'deleted' => 'bg-red-600',
                                                default   => 'bg-neutral-500',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase shadow-sm text-white {{ $colorAccion }} font-sans">
                                            {{ $act->descripcion }}
                                        </span>
                                    </td>

                                    {{-- Elemento (ANTES era Seguimiento, ahora Tarea) --}}
                                    <td class="p-3 text-neutral-600 dark:text-neutral-400 font-sans whitespace-nowrap">
                                        @php
                                            $tipo = class_basename($act->logueable_type);

                                            $nombre = match($tipo) {
                                                'Seguimiento' => 'Tarea',
                                                'Cliente' => 'Cliente',
                                                'Nota' => 'Nota',
                                                default => $tipo,
                                            };
                                        @endphp

                                        <span class="font-bold">{{ $nombre }}</span>
                                        <span class="text-xs text-neutral-400">#{{ $act->logueable_id }}</span>
                                    </td>

                                    {{-- Cambios --}}
                                    <td class="p-3">
                                        @if($act->accion === 'updated' && $act->despues)
                                            <div class="space-y-1 text-[10px] font-sans">
                                                @foreach($act->despues as $campo => $nuevoValor)
                                                    @if(!in_array($campo, ['updated_at', 'created_at']))
                                                        <div class="bg-neutral-50 dark:bg-neutral-800/50 p-1 rounded border border-neutral-100 dark:border-neutral-700">
                                                            <span class="font-bold text-neutral-600 dark:text-neutral-300">{{ $campo }}:</span>
                                                            <span class="text-red-400 line-through px-1">
                                                                {{ $act->antes[$campo] ?? '...' }}
                                                            </span>
                                                            <span class="text-neutral-400 mx-1">→</span>
                                                            <span class="text-green-500 font-bold">
                                                                {{ $nuevoValor }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif($act->accion === 'created')
                                            <span class="text-xs italic text-green-500">✨ Alta de registro completa</span>
                                        @elseif($act->accion === 'deleted')
                                            <span class="text-xs italic text-red-500">🗑️ Registro eliminado permanentemente</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 border-t border-neutral-100 dark:border-neutral-800 pt-4 font-sans text-sm">
                    {{ $actividades->links() }}
                </div>
            @endif

        </div>
    </div>
</x-layouts::app>