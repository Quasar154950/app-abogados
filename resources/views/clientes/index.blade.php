<x-layouts::app :title="__('Clientes')">

    <div class="space-y-6 text-left">

        {{-- Encabezado (Esto se queda acá porque es parte de la página, no de la tabla) --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm font-sans">
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100 italic text-left">Clientes</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed text-left">
                Listado de clientes registrados en el sistema.
            </p>

            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('clientes.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-bold text-white hover:bg-green-700 transition shadow-sm cursor-pointer whitespace-nowrap">
                    <span>➕</span>
                    <span class="inline-block">Agregar nuevo cliente</span>
                </a>

                <a href="{{ route('clientes.archivados') }}"
                   style="background-color: #171717 !important; color: white !important; display: inline-flex !important; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 8px; text-decoration: none; min-width: max-content; font-size: 0.875rem !important; font-weight: 700 !important;"
                   class="cursor-pointer hover:opacity-90 transition shadow-sm">
                    <span>📦</span>
                    <span style="color: white !important;">Ver archivados</span>
                </a>
            </div>
        </div>

        {{-- AQUÍ ESTÁ TODO EL CÓDIGO QUE BORRAMOS --}}
        <livewire:clientes.index-table />

    </div>

</x-layouts::app>