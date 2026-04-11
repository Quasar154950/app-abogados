<x-layouts::app :title="__('Clientes')">

    <div class="space-y-6 text-left">

        {{-- Encabezado --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 md:p-6 bg-white dark:bg-neutral-900 shadow-sm font-sans">
            <h1 class="text-xl md:text-2xl font-bold text-neutral-800 dark:text-neutral-100 italic text-left">
                Clientes
            </h1>

            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed text-left">
                Listado de clientes registrados en el sistema.
            </p>

            <div class="mt-4 flex flex-col sm:flex-row gap-2 sm:gap-3">
                <a href="{{ route('clientes.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-3 md:px-4 py-2 text-sm font-bold text-white hover:bg-green-700 transition shadow-sm cursor-pointer whitespace-nowrap">
                    <span>➕</span>
                    <span>Agregar nuevo cliente</span>
                </a>

                <a href="{{ route('clientes.archivados') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-neutral-900 px-3 md:px-4 py-2 text-sm font-bold text-white hover:opacity-90 transition shadow-sm cursor-pointer whitespace-nowrap">
                    <span>📦</span>
                    <span>Ver archivados</span>
                </a>
            </div>
        </div>

        <livewire:clientes.index-table />

    </div>

</x-layouts::app>