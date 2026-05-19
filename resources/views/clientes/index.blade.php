<x-layouts::app :title="__('Socios')">

    <div class="space-y-4 md:space-y-6 text-left">

        {{-- Encabezado --}}
        <div class="rounded-xl border border-stone-300 p-3 sm:p-4 md:p-6 bg-stone-200 shadow-sm font-sans">

            <h1 class="text-xl md:text-2xl font-bold text-neutral-800 italic text-left">
                Socios
            </h1>

            <p class="mt-2 text-sm text-neutral-600 leading-relaxed text-left">
                Listado de socios registrados en el sistema.
            </p>

            <div class="mt-4 flex gap-2">

                <a href="{{ route('clientes.create') }}"
                   class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 rounded-lg bg-orange-500 px-2 py-2 text-[11px] sm:text-sm font-bold text-white hover:bg-orange-600 transition shadow-sm cursor-pointer whitespace-nowrap overflow-hidden">

                    <span class="shrink-0 text-[10px] sm:text-base">➕</span>

                    <span class="truncate">
                        Agregar nuevo socio
                    </span>

                </a>

                <a href="{{ route('clientes.archivados') }}"
                   class="flex-1 md:flex-none inline-flex items-center justify-center gap-1 rounded-lg bg-neutral-900 px-2 py-2 text-[11px] sm:text-sm font-bold text-white hover:opacity-90 transition shadow-sm cursor-pointer whitespace-nowrap overflow-hidden">

                    <span class="shrink-0 text-[10px] sm:text-base">📦</span>

                    <span class="truncate">
                        Socios inactivos
                    </span>

                </a>

            </div>

        </div>

        <livewire:clientes.index-table />

    </div>

</x-layouts::app>