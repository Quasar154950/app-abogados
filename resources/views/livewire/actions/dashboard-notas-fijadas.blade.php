<div>
    @if($notasFijadas->isNotEmpty())
        <div class="rounded-xl border border-yellow-200 dark:border-yellow-900/40 p-6 bg-white dark:bg-neutral-900 shadow-sm font-sans">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 italic">📌 Notas fijadas</h2>
                    <p class="text-[10px] text-neutral-500 mt-1">Accesos rápidos a notas importantes</p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($notasFijadas as $nota)
                    <div
                        wire:key="dashboard-nota-fijada-{{ $nota->id }}"
                        class="p-4 rounded-lg border border-yellow-100 dark:border-yellow-900/30 bg-yellow-50/60 dark:bg-yellow-900/10"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <a href="{{ route('clientes.show', $nota->cliente_id) }}"
                               class="text-sm font-bold text-neutral-800 dark:text-neutral-100 hover:text-yellow-600 hover:underline transition-colors cursor-pointer">
                                {{ $nota->cliente->nombre ?? 'Sin cliente' }}
                            </a>

                            <button
                                wire:click="togglePin({{ $nota->id }})"
                                type="button"
                                title="Quitar chincheta"
                                class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs shadow-sm hover:scale-110 transition cursor-pointer"
                            >
                                📌
                            </button>
                        </div>

                        <p class="text-xs text-neutral-600 dark:text-neutral-300 mt-2 italic">
                            "{{ $nota->contenido }}"
                        </p>

                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-[10px] text-neutral-400 uppercase italic">
                                {{ $nota->created_at->diffForHumans() }}
                            </span>

                            <a href="{{ route('clientes.show', $nota->cliente_id) }}"
                               class="text-xs font-bold text-yellow-600 hover:underline cursor-pointer">
                                Ver cliente
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>