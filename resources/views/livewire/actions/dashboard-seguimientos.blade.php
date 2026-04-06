<div class="space-y-5">

    {{-- LÓGICA VISUAL DE GESTIÓN --}}
    @php
        $colorRendimiento = $porcentajeRendimiento >= 80
            ? 'bg-green-600'
            : ($porcentajeRendimiento >= 50 ? 'bg-orange-500' : 'bg-red-500');

        $textoRendimiento = $porcentajeRendimiento >= 80
            ? 'text-green-600'
            : ($porcentajeRendimiento >= 50 ? 'text-orange-500' : 'text-red-500');

        $bordeRendimiento = $porcentajeRendimiento >= 80
            ? 'border-green-200 dark:border-green-900/40'
            : ($porcentajeRendimiento >= 50 ? 'border-orange-200 dark:border-orange-900/40' : 'border-red-200 dark:border-red-900/40');

        $fondoRendimiento = $porcentajeRendimiento >= 80
            ? 'bg-green-50/60 dark:bg-green-900/10'
            : ($porcentajeRendimiento >= 50 ? 'bg-orange-50/60 dark:bg-orange-900/10' : 'bg-red-50/60 dark:bg-red-900/10');

        if ($sinActividadHoy) {
            $estadoRendimiento = 'Sin gestión hoy';
            $iconoRendimiento = '🟡';
        } else {
            $estadoRendimiento = $porcentajeRendimiento >= 80
                ? 'Excelente gestión'
                : ($porcentajeRendimiento >= 50 ? 'Gestión intermedia' : 'Gestión baja');

            $iconoRendimiento = $porcentajeRendimiento >= 80
                ? '🔥'
                : ($porcentajeRendimiento >= 50 ? '⚡' : '⚠️');
        }
    @endphp

    {{-- INDICADORES COMPACTOS 2x2 --}}
    <div class="grid grid-cols-2 gap-3 font-sans">

        {{-- Clientes --}}
        <a href="{{ route('clientes.index') }}"
           class="flex items-center gap-3 p-3 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm hover:border-green-500/50 transition-colors group min-h-[76px]">
            <div class="w-2 h-8 bg-green-600 rounded-full shrink-0"></div>
            <div class="min-w-0">
                <p class="text-[9px] font-bold uppercase text-neutral-400 tracking-tight leading-none">Clientes</p>
                <h2 class="text-xl font-black text-neutral-800 dark:text-neutral-100 leading-tight mt-1">{{ $totalClientes }}</h2>
            </div>
        </a>

        {{-- Tareas del caso --}}
        <a href="{{ route('seguimientos.index') }}"
           class="flex items-center gap-3 p-3 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm hover:border-orange-500/50 transition-colors group min-h-[76px]">
            <div class="w-2 h-8 bg-orange-500 rounded-full shrink-0"></div>

            <div class="flex items-center gap-3 min-w-0">
                <div class="min-w-0">
                    <p class="text-[9px] font-bold uppercase text-neutral-400 tracking-tight leading-none">Tareas hoy</p>
                    <h2 class="text-xl font-black text-neutral-800 dark:text-neutral-100 leading-tight mt-1">{{ $cantHoy }}</h2>
                </div>

                <div class="border-l border-neutral-200 dark:border-neutral-700 pl-2">
                    <p class="text-[9px] font-bold uppercase text-red-500/80 tracking-tight leading-none">Vencidas</p>
                    <h2 class="text-xl font-black text-red-600 leading-tight mt-1">{{ $cantVencidos }}</h2>
                </div>
            </div>
        </a>

        {{-- Gestión del día --}}
        <div class="flex items-center gap-3 p-3 {{ $fondoRendimiento }} border {{ $bordeRendimiento }} rounded-xl shadow-sm transition-all duration-300 min-h-[76px]">
            <div class="w-2 h-8 {{ $colorRendimiento }} rounded-full shrink-0"></div>

            <div class="flex-1 min-w-0">
                <p class="text-[9px] font-bold uppercase text-neutral-400 tracking-tight leading-none">
                    Gestión del día
                </p>

                <div class="flex items-baseline gap-2 mt-1 flex-wrap">
                    <h2 class="text-xl font-black text-neutral-800 dark:text-neutral-100 leading-tight">
                        {{ $porcentajeRendimiento }}%
                    </h2>

                    <span class="text-[9px] font-bold uppercase {{ $textoRendimiento }}">
                        {{ $resueltosHoy }} completadas
                    </span>
                </div>

                <p class="text-[9px] mt-1 font-medium {{ $textoRendimiento }} flex items-center gap-1 leading-tight">
                    <span>{{ $iconoRendimiento }}</span>
                    <span>{{ $estadoRendimiento }}</span>
                </p>
            </div>
        </div>

        {{-- Notas --}}
        <div class="flex items-center gap-3 p-3 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm min-h-[76px]">
            <div class="w-2 h-8 bg-blue-600 rounded-full shrink-0"></div>
            <div class="min-w-0">
                <p class="text-[9px] font-bold uppercase text-neutral-400 tracking-tight leading-none">Notas hoy</p>
                <h2 class="text-xl font-black text-neutral-800 dark:text-neutral-100 leading-tight mt-1">{{ $soloHoyNotas }}</h2>
            </div>
        </div>
    </div>

    {{-- ALERTA DE GESTIÓN BAJA --}}
    @if(!$sinActividadHoy && $porcentajeRendimiento < 50)
        <div class="rounded-xl border border-red-200 dark:border-red-900/40 bg-red-50 dark:bg-red-900/10 px-4 py-3 shadow-sm font-sans">
            <p class="text-xs font-bold text-red-700 dark:text-red-400">
                🔻 La gestión del día está baja. Conviene revisar tareas pendientes del estudio.
            </p>
        </div>
    @endif

    {{-- LÓGICA VISUAL DE SALUD --}}
    @php
        $colorBarra = $porcentaje >= 80 ? 'bg-green-600' : ($porcentaje >= 50 ? 'bg-orange-500' : 'bg-red-500');
        $colorTexto = str_replace('bg-', 'text-', $colorBarra);

        $estadoSalud = $porcentaje >= 80
            ? 'Óptimo'
            : ($porcentaje >= 50 ? 'Estable' : 'Crítico');

        $badgeSalud = $porcentaje >= 80
            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
            : ($porcentaje >= 50
                ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'
                : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400');
    @endphp

    {{-- BARRA DE SALUD GENERAL --}}
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm font-sans">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3 mb-3">
            <div>
                <h3 class="text-sm font-bold text-neutral-800 dark:text-neutral-100 italic">Estado general de tareas</h3>
                <p class="text-[10px] text-neutral-500 leading-relaxed">
                    Porcentaje de tareas no vencidas respecto al total de tareas activas del estudio
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-lg font-black {{ $colorTexto }}">{{ $porcentaje }}%</span>
                <span class="text-[10px] font-bold px-2 py-1 rounded {{ $badgeSalud }}">
                    {{ $estadoSalud }}
                </span>
            </div>
        </div>

        <div class="relative w-full bg-neutral-200 dark:bg-neutral-800 rounded-full h-6 overflow-hidden border border-neutral-100 dark:border-neutral-700 shadow-inner">
            <div class="{{ $colorBarra }} h-full transition-all duration-1000 ease-out flex items-center justify-center relative shadow-[0_0_15px_rgba(0,0,0,0.1)_inset]"
                 style="width: {{ $porcentaje }}%; background-image: linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent); background-size: 1rem 1rem;">
                <div class="absolute top-0 left-0 w-full h-1/2 bg-white/20 rounded-full"></div>

                @if($porcentaje > 15)
                    <span class="text-[10px] font-black text-white drop-shadow-md tracking-tight uppercase">
                        {{ $porcentaje == 100 ? '¡Al día!' : 'En gestión' }}
                    </span>
                @endif
            </div>
        </div>

        <p class="mt-2 text-[10px] text-neutral-400 italic leading-relaxed">
            @if($porcentaje == 100)
                Estado: Óptimo. No hay tareas activas vencidas. 🏆
            @elseif($porcentaje >= 80)
                Estado: Muy bueno. La mayoría de las tareas del estudio están en término. 💪
            @elseif($porcentaje >= 50)
                Estado: Atención. Ya hay tareas vencidas que conviene revisar. 👀
            @else
                Estado: Crítico. Hay varias tareas vencidas y conviene priorizarlas. ☕
            @endif
        </p>
    </div>

    {{-- ALERTAS CRÍTICAS --}}
    @if($vencidos->isNotEmpty() || $paraHoy->isNotEmpty())
        <div class="grid gap-4 md:grid-cols-2">

            {{-- TAREAS VENCIDAS --}}
            @if($vencidos->isNotEmpty())
                <div class="rounded-xl border border-red-200 dark:border-red-900/50 p-4 bg-red-50 dark:bg-red-900/20 shadow-sm font-sans">
                    <h2 class="text-sm font-bold text-red-700 dark:text-red-400 flex items-center gap-2 mb-3">
                        ⚠️ TAREAS VENCIDAS: {{ $cantVencidos }}
                    </h2>

                    <div class="space-y-2">
                        @foreach($vencidos as $vencido)
                            <div class="text-xs bg-white/60 dark:bg-neutral-800/50 p-2.5 rounded-lg border border-red-100 dark:border-red-900/30 flex justify-between items-center gap-2">
                                <a href="{{ route('clientes.show', $vencido->cliente_id) }}"
                                   class="font-bold text-neutral-700 dark:text-neutral-200 flex-1 hover:text-red-600 hover:underline transition-colors cursor-pointer">
                                    {{ $vencido->cliente->nombre }}
                                </a>

                                <div class="flex items-center gap-3 shrink-0">
                                    <button
                                        type="button"
                                        wire:click="marcarComoResuelto({{ $vencido->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="marcarComoResuelto({{ $vencido->id }})"
                                        class="text-green-600 hover:scale-125 transition-transform cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Marcar como completada"
                                    >
                                        @if($procesandoId === $vencido->id)
                                            ⏳
                                        @else
                                            ✅
                                        @endif
                                    </button>

                                    <a href="{{ route('clientes.show', $vencido->cliente_id) }}"
                                       class="text-red-600 font-bold hover:underline cursor-pointer">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- TAREAS PARA HOY --}}
            @if($paraHoy->isNotEmpty())
                <div class="rounded-xl border border-orange-200 dark:border-orange-900/50 p-4 bg-orange-50 dark:bg-orange-900/20 shadow-sm font-sans">
                    <h2 class="text-sm font-bold text-orange-700 dark:text-orange-400 flex items-center gap-2 mb-3">
                        📅 TAREAS PARA HOY: {{ $cantHoy }}
                    </h2>

                    <div class="space-y-2">
                        @foreach($paraHoy as $hoy)
                            <div class="text-xs bg-white/60 dark:bg-neutral-800/50 p-2.5 rounded-lg border border-orange-100 dark:border-orange-900/30 flex justify-between items-center gap-2">
                                <a href="{{ route('clientes.show', $hoy->cliente_id) }}"
                                   class="font-bold text-neutral-700 dark:text-neutral-200 flex-1 hover:text-orange-600 hover:underline transition-colors cursor-pointer">
                                    {{ $hoy->cliente->nombre }}
                                </a>

                                <div class="flex items-center gap-3 shrink-0">
                                    <button
                                        type="button"
                                        wire:click="marcarComoResuelto({{ $hoy->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="marcarComoResuelto({{ $hoy->id }})"
                                        class="text-green-600 hover:scale-125 transition-transform cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Marcar como completada"
                                    >
                                        @if($procesandoId === $hoy->id)
                                            ⏳
                                        @else
                                            ✅
                                        @endif
                                    </button>

                                    <a href="{{ route('clientes.show', $hoy->cliente_id) }}"
                                       class="text-orange-600 font-bold hover:underline cursor-pointer">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>
