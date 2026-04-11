<div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 md:p-6 bg-white dark:bg-neutral-900 shadow-sm font-sans">
    
    {{-- BUSCADOR CON LUPA REDONDEADA INTERNA --}}
    <div class="mb-6 md:mb-8 flex items-center gap-2 text-left">
        
        {{-- Contenedor principal del buscador --}}
        <div class="flex-1 min-w-0 flex items-center bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 rounded-xl p-1 focus-within:ring-2 focus-within:ring-blue-500 transition shadow-sm">
            
            {{-- INPUT --}}
            <input 
                wire:model.live.debounce.300ms="busqueda" 
                type="text" 
                placeholder="Buscar por nombre, email o teléfono..."
                class="flex-1 min-w-0 bg-transparent pl-3 py-1.5 border-none focus:ring-0 outline-none text-sm text-neutral-800 dark:text-neutral-200"
            >

            {{-- LUPITA --}}
            <div class="flex items-center justify-center bg-neutral-100 dark:bg-neutral-800 text-neutral-500 rounded-lg px-3 py-1.5 ml-1 border border-transparent dark:border-neutral-700 shrink-0">
                🔍
            </div>
        </div>

        {{-- BOTÓN LIMPIAR --}}
        <button type="button"
                wire:click="$set('busqueda', '')"
                class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-neutral-200 dark:bg-neutral-800 px-3 py-2 text-sm font-medium text-neutral-600 dark:text-neutral-300 hover:bg-neutral-300 dark:hover:bg-neutral-700 transition cursor-pointer shadow-sm whitespace-nowrap">
            🧹 Limpiar
        </button>
    </div>

    {{-- MENSAJE DE ÉXITO --}}
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)" 
             class="mb-4 p-4 rounded-xl bg-green-50 dark:bg-neutral-800 border border-green-200 dark:border-green-900/50 text-green-700 dark:text-green-400 text-sm font-bold flex items-center gap-2 transition-all">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- TABLA DE CLIENTES --}}
    @if($clientes->isEmpty())
        <div class="text-center py-10">
            <p class="text-neutral-500 italic">No se encontraron clientes activos.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-700 text-neutral-400 text-[11px] uppercase tracking-wider font-bold">
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Nombre</th>
                        <th class="p-3 text-left">Teléfono</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                    @foreach($clientes as $cliente)
                        <tr wire:key="cliente-{{ $cliente->id }}" class="hover:bg-neutral-50 dark:hover:bg-neutral-800/40 transition">
                            <td class="p-3 text-sm text-neutral-500 font-mono">#{{ $cliente->id }}</td>
                            <td class="p-3 text-sm font-bold text-neutral-800 dark:text-neutral-200">{{ $cliente->nombre }}</td>
                            <td class="p-3 text-sm text-neutral-600 dark:text-neutral-400 font-medium">{{ $cliente->telefono }}</td>
                            <td class="p-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $cliente->email }}</td>
                            
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" 
                                       class="p-2 rounded-lg text-neutral-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition cursor-pointer" 
                                       title="Ver Detalle">👁️</a>
                                    
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                       class="p-2 rounded-lg text-neutral-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition cursor-pointer" 
                                       title="Editar">✏️</a>
                                    
                                    <button 
                                         wire:click="archivar({{ $cliente->id }})" 
                                         wire:confirm="¿Quieres archivar a este cliente?"
                                         class="p-2 text-neutral-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition rounded-lg cursor-pointer"
                                         title="Archivar Cliente">
                                        📦
                                    </button>

                                    <button 
                                        wire:click="delete({{ $cliente->id }})" 
                                        wire:confirm="¿Estás seguro de que deseas eliminar permanentemente a este cliente?"
                                        class="p-2 text-neutral-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition rounded-lg cursor-pointer"
                                        title="Eliminar Permanentemente">
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-6">
            {{ $clientes->links() }}
        </div>
    @endif
</div>

<style>
    nav[role="navigation"] a, 
    nav[role="navigation"] button {
        cursor: pointer !important;
    }
</style>