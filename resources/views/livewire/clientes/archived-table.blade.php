<div class="space-y-6">

    {{-- CABECERA --}}
    <div class="rounded-xl border border-stone-300 p-4 md:p-6 bg-stone-200 shadow-sm">

        <div class="flex items-center gap-4">

            <div>

                <h1 class="text-xl md:text-2xl font-bold text-stone-900">
                    Socios Dados de Baja
                </h1>

                <p class="mt-2 text-sm text-stone-600">
                    Lista de socios archivados. Podés restaurarlos o eliminarlos permanentemente.
                </p>

            </div>

        </div>

    </div>

    {{-- CUERPO --}}
    <div class="rounded-xl border border-stone-300 p-4 md:p-6 bg-stone-200 shadow-sm font-sans">

        {{-- BUSCADOR --}}
        <div class="mb-6 md:mb-8 flex items-center gap-2 text-left">

            <div class="flex-1 min-w-0 flex items-center bg-stone-100 border border-stone-300 rounded-xl p-1 focus-within:ring-2 focus-within:ring-orange-500 transition shadow-sm">

                <input 
                    wire:model.live.debounce.300ms="busqueda" 
                    type="text" 
                    placeholder="Buscar en socios dados de baja..."
                    class="flex-1 min-w-0 bg-transparent pl-3 py-1.5 border-none focus:ring-0 outline-none text-sm text-stone-800"
                >

                <div class="flex items-center justify-center bg-stone-100 text-stone-500 rounded-lg px-3 py-1.5 ml-1 border border-stone-300 shrink-0">
                    🔍
                </div>

            </div>

            <button
                type="button"
                wire:click="$set('busqueda', '')"
                class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-stone-100 px-3 py-2 text-sm font-medium text-stone-700 hover:bg-stone-300 transition cursor-pointer shadow-sm whitespace-nowrap border border-stone-300"
            >
                🧹 Limpiar
            </button>

        </div>

        {{-- MENSAJE --}}
        @if(session('success'))

            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 3000)"
                class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold flex items-center gap-2 transition-all"
            >
                ✅ {{ session('success') }}
            </div>

        @endif

        @if($clientes->isEmpty())

            <div class="text-center py-10">

                <p class="text-stone-500 italic">
                    No hay socios dados de baja.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="border-b border-stone-300 text-stone-500 text-[11px] uppercase tracking-wider font-bold">

                            <th class="p-3 text-left">ID</th>

                            <th class="p-3 text-left">Nombre</th>

                            <th class="p-3 text-left">Teléfono</th>

                            <th class="p-3 text-left">Email</th>

                            <th class="p-3 text-right">Acciones</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-stone-300">

                        @foreach($clientes as $cliente)

                            <tr wire:key="archived-{{ $cliente->id }}" class="hover:bg-stone-100 transition">

                                <td class="p-3 text-sm text-stone-500 font-mono">
                                    #{{ $cliente->id }}
                                </td>

                                <td class="p-3 text-sm font-bold text-stone-800 whitespace-nowrap">
                                    {{ $cliente->nombre }}
                                </td>

                                <td class="p-3 text-sm text-stone-600 font-medium">
                                    {{ $cliente->telefono }}
                                </td>

                                <td class="p-3 text-sm text-stone-600">
                                    {{ $cliente->email }}
                                </td>

                                <td class="p-3 text-right">

                                    <div class="flex items-center justify-end gap-1">

                                        <a
                                            href="{{ route('clientes.show', $cliente->id) }}"
                                            class="p-2 rounded-lg text-stone-500 hover:text-blue-600 hover:bg-blue-50 transition cursor-pointer"
                                            title="Ver"
                                        >
                                            👁️
                                        </a>

                                        <button
                                            wire:click="desarchivar({{ $cliente->id }})"
                                            wire:confirm="¿Restaurar socio?"
                                            class="p-2 text-stone-500 hover:text-green-600 hover:bg-green-50 transition rounded-lg cursor-pointer"
                                            title="Restaurar"
                                        >
                                            🔄
                                        </button>

                                        <button
                                            wire:click="delete({{ $cliente->id }})"
                                            wire:confirm="¿Eliminar para siempre?"
                                            class="p-2 text-stone-500 hover:text-red-600 hover:bg-red-50 transition rounded-lg cursor-pointer"
                                            title="Eliminar"
                                        >
                                            🗑️
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="mt-6">
                {{ $clientes->links() }}
            </div>

        @endif

        {{-- BOTÓN VOLVER --}}
        <div class="mt-8 pt-6 border-t border-stone-300">

            <a
                href="{{ route('clientes.index') }}"
                style="background:black;color:white;border-radius:14px;padding:10px 16px;font-size:14px;font-weight:bold;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;"
            >
                ⬅️ Volver al listado general
            </a>

        </div>

    </div>

</div>