<x-layouts::app :title="__('Dashboard')">

    <div class="space-y-5 text-left">

        {{-- 1. MENSAJE DE ÉXITO --}}
        @if(session('success'))
            <div class="p-3 rounded-xl bg-green-50 dark:bg-neutral-800 border border-green-200 dark:border-green-900/50 text-green-700 dark:text-green-400 text-sm font-bold shadow-sm flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- CABECERA --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm font-sans">
            <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-100 italic">Panel del Estudio</h1>
            <p class="mt-1.5 text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed">
                Bienvenido. Acá tenés un resumen del estado general de tus clientes y tareas del estudio.
            </p>
        </div>

        {{-- BLOQUE VIVO DE TAREAS --}}
        <livewire:actions.dashboard-seguimientos
            :total-clientes="$totalClientes"
            :solo-hoy-notas="$soloHoyNotas"
        />

        {{-- BOTONES DE GESTIÓN --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm font-sans">
            <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 italic">Gestión del Estudio</h2>

            <div class="mt-4 flex flex-wrap gap-2.5">
                <a href="{{ route('clientes.index') }}"
                   class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-bold shadow-sm transition cursor-pointer active:scale-95"
                   style="background-color: #2563eb; color: #ffffff !important;">
                    👁️ Ver clientes
                </a>

                <a href="{{ route('seguimientos.index') }}"
                   class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-bold shadow-sm transition cursor-pointer active:scale-95"
                   style="background-color: #262626; color: #ffffff !important; min-width: 170px; gap: 8px;">
                    🔍 Ver tareas del caso
                </a>

                <a href="{{ route('clientes.create') }}"
                   class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-bold shadow-sm transition cursor-pointer active:scale-95"
                   style="background-color: #16a34a; color: #ffffff !important;">
                    ➕ Nuevo cliente
                </a>
            </div>
        </div>

        {{-- NOTAS FIJADAS CON LIVEWIRE --}}
        <livewire:actions.dashboard-notas-fijadas />

        {{-- SECCIÓN INFERIOR --}}
        <div class="grid gap-4 md:grid-cols-2">

            {{-- Próximos Recordatorios --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm font-sans">
                <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 italic mb-4">📅 Próximos Recordatorios</h2>

                <div class="space-y-3">
                    @forelse($proximosRecordatorios as $recordatorio)
                        <div class="p-3 rounded-lg border border-neutral-100 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-800/50">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                <span class="text-sm font-bold text-neutral-700 dark:text-neutral-200">
                                    {{ $recordatorio->cliente->nombre ?? 'Sin Cliente' }}
                                </span>

                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded bg-blue-500 text-white shadow-sm inline-block w-fit">
                                    {{ str_replace('_', ' ', $recordatorio->estado) }}
                                </span>
                            </div>

                            <p class="text-xs text-neutral-500 mt-2 italic leading-relaxed">"{{ $recordatorio->descripcion }}"</p>
                        </div>
                    @empty
                        <p class="text-xs text-neutral-500 italic text-center py-5">No hay recordatorios futuros.</p>
                    @endforelse
                </div>
            </div>

            {{-- Últimos Ingresos --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm font-sans">
                <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 italic mb-4">🆕 Últimos clientes incorporados</h2>

                <div class="space-y-3">
                    @foreach($ultimosClientes as $cliente)
                        <div class="flex items-center justify-between text-sm border-b border-neutral-100 dark:border-neutral-800 pb-2 last:border-0">
                            <a href="{{ route('clientes.show', $cliente->id) }}"
                               class="text-neutral-700 dark:text-neutral-300 font-medium hover:text-blue-600 hover:underline transition-colors cursor-pointer">
                                {{ $cliente->nombre }}
                            </a>

                            <span class="text-[10px] text-neutral-400 uppercase italic shrink-0">
                                {{ $cliente->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>