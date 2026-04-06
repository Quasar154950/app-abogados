<div>
    {{-- El modal de Flux que se abre cuando showModal es true --}}
    <flux:modal name="global-search-modal" x-model="$wire.showModal" variant="thin" class="p-0" x-on:open-global-search.window="$wire.showModal = true">
        <div class="p-4 border-b border-zinc-100 dark:border-zinc-800">
            <flux:input 
                wire:model.live.debounce.300ms="query" 
                placeholder="Buscar clientes o contenido de notas..." 
                icon="magnifying-glass"
                autofocus
            />
        </div>

        <div class="max-h-96 overflow-y-auto p-2">
            @if(!empty($results['clientes']) || !empty($results['notas']))
                
                {{-- SECCIÓN: CLIENTES --}}
                @if(!empty($results['clientes']))
                    <div class="px-3 py-2 text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Clientes</div>
                    <div class="space-y-1 mb-4">
                        @foreach($results['clientes'] as $cliente)
                            <a href="{{ route('clientes.show', $cliente['id']) }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all group cursor-pointer active:scale-[0.98]">
                                <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600">
                                    <flux:icon name="user" variant="micro" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-zinc-800 dark:text-white truncate group-hover:text-green-600 transition-colors">
                                        {{ $cliente['nombre'] }}
                                    </div>
                                    <div class="text-[11px] text-zinc-500">
                                        {{ $cliente['telefono'] ?? 'Sin teléfono' }}
                                    </div>
                                </div>
                                <flux:icon name="chevron-right" variant="micro" class="text-zinc-300 group-hover:text-zinc-500" />
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- SECCIÓN: NOTAS --}}
                @if(!empty($results['notas']))
                    <div class="px-3 py-2 text-[10px] font-bold text-zinc-400 uppercase tracking-widest border-t dark:border-zinc-800 pt-4">Encontrado en Notas</div>
                    <div class="space-y-1">
                        @foreach($results['notas'] as $nota)
                            <a href="{{ route('clientes.show', $nota['cliente_id']) }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all group cursor-pointer active:scale-[0.98]">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                    <flux:icon name="document-text" variant="micro" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[10px] font-bold text-blue-500 uppercase">{{ $nota['cliente_nombre'] }}</div>
                                    <div class="text-sm text-zinc-600 dark:text-zinc-300 italic truncate">
                                        "{{ $nota['contenido'] }}"
                                    </div>
                                </div>
                                <flux:icon name="chevron-right" variant="micro" class="text-zinc-300 group-hover:text-zinc-500" />
                            </a>
                        @endforeach
                    </div>
                @endif

            @elseif(strlen($query) >= 2)
                <div class="p-8 text-center">
                    <flux:text variant="body" class="text-zinc-500">
                        No hay resultados para "{{ $query }}".
                    </flux:text>
                </div>
            @else
                <div class="p-8 text-center">
                    <flux:text variant="body" class="text-zinc-400 italic text-xs">
                        Escribí algo para buscar en toda la App...
                    </flux:text>
                </div>
            @endif
        </div>

        {{-- Pie del modal --}}
        <div class="p-3 bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center text-[10px] text-zinc-400 uppercase tracking-widest px-4">
            <span>ESC para cerrar</span>
            <span>Total: {{ (count($results['clientes'] ?? [])) + (count($results['notas'] ?? [])) }}</span>
        </div>
    </flux:modal>
</div>