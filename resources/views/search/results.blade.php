<x-layouts::app :title="'Resultados para: ' . $query">

    <div class="space-y-6 pb-10 text-left px-4">
        
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100 italic">
                Resultados de búsqueda
            </h1>
            <p class="text-sm text-neutral-500 mt-1">
                Mostrando resultados para: <span class="font-bold text-green-600">"{{ $query }}"</span>
            </p>
        </div>

        @if($clientes->isEmpty() && $notas->isEmpty())
            <div class="p-10 text-center rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700">
                <p class="text-neutral-500 italic">No encontramos nada que coincida. 🧐</p>
                <a href="{{ route('dashboard') }}" class="text-green-600 text-sm font-bold mt-2 inline-block hover:underline">Volver al Dashboard</a>
            </div>
        @else

            @if($clientes->isNotEmpty())
                <div class="space-y-3">
                    <h2 class="text-[10px] font-bold uppercase text-neutral-400 tracking-widest px-1">Clientes encontrados ({{ $clientes->count() }})</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($clientes as $cliente)
                            <a href="{{ route('clientes.show', $cliente->id) }}" class="group block p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 hover:border-green-500 transition shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center text-neutral-500 font-bold uppercase border border-neutral-200 dark:border-neutral-700">
                                        {{ substr($cliente->nombre, 0, 1) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-bold text-neutral-800 dark:text-neutral-100 group-hover:text-green-600 transition truncate">
                                            {{ $cliente->nombre }}
                                        </p>
                                        <p class="text-[10px] text-neutral-500 italic">
                                            {{ $cliente->telefono ?? 'Sin teléfono' }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($notas->isNotEmpty())
                <div class="space-y-3 mt-8">
                    <h2 class="text-[10px] font-bold uppercase text-neutral-400 tracking-widest px-1">Notas relacionadas ({{ $notas->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($notas as $nota)
                            <div class="p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-neutral-200 dark:border-neutral-700 group-hover:bg-green-500 transition-colors"></div>
                                
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 italic leading-relaxed">
                                    {{-- CORREGIDO: Usamos 'contenido' en lugar de 'descripcion' --}}
                                    "{{ Str::limit($nota->contenido, 150) }}"
                                </p>
                                
                                <div class="mt-3 flex justify-between items-center border-t border-neutral-100 dark:border-neutral-800 pt-3">
                                    <span class="text-[10px] text-neutral-400 uppercase font-medium">
                                        {{ $nota->created_at->format('d/m/Y') }}
                                    </span>
                                    <a href="{{ route('clientes.show', $nota->cliente_id) }}" class="text-[10px] font-bold text-green-600 hover:text-green-700 hover:underline">
                                        Ver cliente: {{ $nota->cliente->nombre ?? 'Ir a la ficha' }} →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif
    </div>

</x-layouts::app>
