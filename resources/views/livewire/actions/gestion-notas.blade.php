<div class="space-y-6">
    {{-- Formulario --}}
    <div class="rounded-xl border border-neutral-200 p-6 bg-white shadow-sm dark:bg-neutral-900 dark:border-neutral-700">
        <h2 class="text-xl font-bold mb-4 text-left dark:text-white text-neutral-800">Agregar Nota</h2>
        
        <textarea wire:model="contenido" rows="3" 
                  class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-800 dark:border-neutral-700 focus:ring-2 focus:ring-green-500 outline-none text-sm text-neutral-800 dark:text-neutral-200 font-normal" 
                  placeholder="Escribe una nota rápida..."></textarea>
        
        @error('contenido') <span class="text-xs text-red-500 block mt-1 font-bold italic">{{ $message }}</span> @enderror
        
        <button wire:click="agregarNota" 
                wire:loading.attr="disabled"
                class="mt-4 inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-10 py-3 text-sm font-bold text-white hover:bg-green-700 transition w-full md:w-auto cursor-pointer active:scale-95 disabled:opacity-50 shadow-md">
            <span wire:loading.remove wire:target="agregarNota">✔ Guardar nota</span>
            <span wire:loading wire:target="agregarNota">⏳ Guardando...</span>
        </button>
    </div>

    {{-- Listado --}}
    <div class="rounded-xl border border-neutral-200 p-6 bg-white shadow-sm dark:bg-neutral-900 dark:border-neutral-700">
        <h2 class="text-xl font-bold mb-4 text-neutral-400 text-left">Historial de Notas</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-left">
            @forelse($notas as $nota)
                <div wire:key="nota-{{ $nota->id }}" 
                     class="rounded-lg p-3 flex justify-between items-start gap-4 shadow-sm transition-all"
                     style="{{ $nota->is_pinned ? 'border: 2px solid #eab308; background-color: #fefce8;' : 'border: 1px solid #e5e7eb; background-color: #f9fafb;' }}">
                    
                    <div class="flex-1">
                        <p class="text-sm {{ $nota->is_pinned ? 'text-yellow-900 font-medium' : 'text-neutral-700' }} font-normal">
                            @if($nota->is_pinned) 📌 @endif {{ $nota->contenido }}
                        </p>
                        <p class="text-[10px] text-neutral-400 mt-1 uppercase font-bold tracking-tighter">
                            {{ $nota->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="flex gap-1 items-center shrink-0">
                        {{-- Fijar/Desfijar --}}
                        <button wire:click="togglePin({{ $nota->id }})" 
                                class="p-1.5 text-yellow-600 hover:scale-125 transition cursor-pointer opacity-70 hover:opacity-100" 
                                title="{{ $nota->is_pinned ? 'Quitar chincheta' : 'Fijar nota arriba' }}">
                            📌
                        </button>

                        {{-- Editar --}}
                        <a href="{{ route('notas.edit', $nota->id) }}" 
                           class="p-1.5 text-yellow-600 hover:scale-125 transition cursor-pointer opacity-70 hover:opacity-100" 
                           title="Editar nota">
                           ✏️
                        </a>

                        {{-- Eliminar --}}
                        <button wire:click="borrar({{ $nota->id }})" 
                                wire:confirm="¿Estás seguro de que deseas borrar esta nota?" 
                                class="p-1.5 text-red-600 hover:scale-125 transition cursor-pointer opacity-70 hover:opacity-100" 
                                title="Eliminar nota">
                            🗑️
                        </button>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-sm text-neutral-400 italic font-normal">No hay notas todavía.</p>
            @endforelse
        </div>
    </div>
</div>
