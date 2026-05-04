<x-layouts::app :title="__('Dashboard')">

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="space-y-5 text-left">

        {{-- 1. MENSAJE DE ÉXITO --}}
        @if(session('success'))
            <div class="p-3 rounded-xl bg-green-50 dark:bg-neutral-800 border border-green-200 dark:border-green-900/50 text-green-700 dark:text-green-400 text-sm font-bold shadow-sm flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- 🔥 AVISO SaaS --}}
        @if(!is_null($diasRestantes) && $diasRestantes <= 7)

            @php
                if ($diasRestantes > 3) {
                    $bg = '#fef9c3';
                    $color = '#92400e';
                    $border = '#fde68a';
                    $badge = 'Por vencer';
                    $badgeColor = '#f59e0b';
                } else {
                    $bg = '#fee2e2';
                    $color = '#991b1b';
                    $border = '#fecaca';
                    $badge = 'Urgente';
                    $badgeColor = '#dc2626';
                }
            @endphp

            <div class="p-4 rounded-2xl shadow-sm flex items-center justify-between gap-3"
                 style="background: {{ $bg }}; color: {{ $color }}; border:1px solid {{ $border }}; animation: fadeIn .3s ease-in-out;">

                <div>
                    <div class="font-black text-sm">
                        @if($diasRestantes <= 1)
                            ⚠️ Suscripción por vencer
                        @else
                            ⏳ Suscripción activa
                        @endif
                    </div>

                    <div class="text-xs mt-1">
                        @if($diasRestantes === 0)
                            Vence hoy. Renová para evitar suspensión.
                        @elseif($diasRestantes === 1)
                            Vence mañana. Te recomendamos renovar.
                        @else
                            Te quedan {{ $diasRestantes }} días de suscripción.
                        @endif

                        {{-- 🔥 BOTÓN CLAVE --}}
                        <a href="{{ route('suscripcion.index') }}"
   class="inline-block mt-2 px-3 py-1 rounded bg-white text-black text-xs font-bold shadow">
    Ir a suscripción
</a>
                    </div>
                </div>

                <span style="background: {{ $badgeColor }}; color:white; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:bold;">
                    {{ $badge }}
                </span>

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

            <div class="mt-4 flex gap-2.5 overflow-x-auto md:overflow-visible whitespace-nowrap md:whitespace-normal md:flex-wrap">

                <a href="{{ route('clientes.index') }}"
                   class="shrink-0 inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-bold shadow-sm transition cursor-pointer active:scale-95"
                   style="background-color: #2563eb; color: #ffffff !important;">
                    👁️ Ver clientes
                </a>

                <a href="{{ route('seguimientos.index') }}"
                   class="shrink-0 inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-bold shadow-sm transition cursor-pointer active:scale-95"
                   style="background-color: #262626; color: #ffffff !important; min-width: 170px;">
                    🔍 Ver tareas
                </a>

                <a href="{{ route('clientes.create') }}"
                   class="shrink-0 inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-bold shadow-sm transition cursor-pointer active:scale-95"
                   style="background-color: #16a34a; color: #ffffff !important;">
                    ➕ Nuevo cliente
                </a>

            </div>

        </div>

        {{-- NOTAS FIJADAS --}}
        <livewire:actions.dashboard-notas-fijadas />

        {{-- SECCIÓN INFERIOR --}}
        <div class="grid gap-4 md:grid-cols-2">

            {{-- Próximos Recordatorios --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm font-sans">
                <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-100 italic mb-4">📅 Próximos Recordatorios</h2>

                <div class="space-y-3">
                    @forelse($proximosRecordatorios as $recordatorio)
                        <div class="p-3 rounded-lg border border-neutral-100 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-800/50">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold">
                                    {{ $recordatorio->cliente->nombre ?? 'Sin Cliente' }}
                                </span>

                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded bg-blue-500 text-white">
                                    {{ str_replace('_', ' ', $recordatorio->estado) }}
                                </span>
                            </div>

                            <p class="text-xs text-neutral-500 mt-2 italic">
                                "{{ $recordatorio->descripcion }}"
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-neutral-500 italic text-center py-5">
                            No hay recordatorios futuros.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Últimos clientes --}}
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm font-sans">
                <h2 class="text-lg font-bold mb-4">🆕 Últimos clientes incorporados</h2>

                <div class="space-y-3">
                    @foreach($ultimosClientes as $cliente)
                        <div class="flex justify-between text-sm border-b pb-2">
                            <a href="{{ route('clientes.show', $cliente->id) }}"
                               class="hover:underline">
                                {{ $cliente->nombre }}
                            </a>

                            <span class="text-xs text-neutral-400">
                                {{ $cliente->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</x-layouts::app>
