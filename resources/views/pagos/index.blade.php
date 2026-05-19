<x-layouts::app :title="'Pagos / Cuotas'">

    <div class="-m-4 min-h-screen space-y-6 bg-slate-950 p-4 sm:-m-6 sm:p-6">

        {{-- ENCABEZADO --}}
        <div class="rounded-xl border border-stone-300 dark:border-stone-600 bg-stone-200 dark:bg-stone-800 p-6 shadow-sm">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>

                    <h1 class="text-2xl font-black text-stone-900 dark:text-stone-100">
                        💰 Pagos / Cuotas
                    </h1>

                    <p class="mt-2 text-sm text-stone-600 dark:text-stone-300">
                        Estado general de cuotas y vencimientos de socios.
                    </p>

                </div>

                <div class="inline-flex items-center rounded-full bg-orange-500/20 px-4 py-2 text-xs font-black text-orange-500 border border-orange-500/30">
                    👥 {{ $clientes->count() }} socios
                </div>

            </div>

        </div>

        {{-- LISTADO --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

            @foreach($clientes as $cliente)

                @php

                    $hoy = \Carbon\Carbon::today();

                    $vence = $cliente->fecha_vencimiento_cuota
                        ? \Carbon\Carbon::parse($cliente->fecha_vencimiento_cuota)
                        : null;

                    if (!$vence) {

                        $badge = 'bg-stone-300 dark:bg-stone-700 text-stone-700 dark:text-stone-200';
                        $textoEstado = 'Sin vencimiento';

                    } elseif ($vence->lt($hoy)) {

                        $badge = 'bg-red-500/20 text-red-600 dark:text-red-300 border border-red-500/30';
                        $textoEstado = 'Cuota vencida';

                    } elseif ($vence->diffInDays($hoy) <= 3) {

                        $badge = 'bg-yellow-500/20 text-yellow-700 dark:text-yellow-300 border border-yellow-500/30';
                        $textoEstado = 'Próxima a vencer';

                    } else {

                        $badge = 'bg-green-500/20 text-green-700 dark:text-green-300 border border-green-500/30';
                        $textoEstado = 'Al día';

                    }

                    $telefono = preg_replace('/\D/', '', $cliente->telefono);

                @endphp

                <div class="rounded-xl border border-stone-300 dark:border-stone-600 bg-stone-200 dark:bg-stone-800 shadow-sm p-5">

                    {{-- CABECERA --}}
                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <h2 class="text-lg font-black text-stone-900 dark:text-stone-100">
                                👤 {{ $cliente->nombre }}
                            </h2>

                            <p class="mt-1 text-sm text-stone-600 dark:text-stone-300">
                                📞 {{ $cliente->telefono }}
                            </p>

                        </div>

                        <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-black {{ $badge }}">
                            {{ $textoEstado }}
                        </span>

                    </div>

                    {{-- INFO --}}
                    <div class="mt-5 grid grid-cols-2 gap-3">

                        <div class="rounded-xl bg-stone-100 dark:bg-stone-700 p-4 border border-stone-300 dark:border-stone-600">

                            <p class="text-[11px] uppercase font-black text-stone-500 dark:text-stone-300">
                                Vencimiento
                            </p>

                            <p class="mt-1 text-sm font-black text-stone-900 dark:text-stone-100">

                                @if($vence)
                                    📅 {{ $vence->format('d/m/Y') }}
                                @else
                                    —
                                @endif

                            </p>

                        </div>

                        <div class="rounded-xl bg-stone-100 dark:bg-stone-700 p-4 border border-stone-300 dark:border-stone-600">

                            <p class="text-[11px] uppercase font-black text-stone-500 dark:text-stone-300">
                                Estado
                            </p>

                            <p class="mt-1 text-sm font-black text-stone-900 dark:text-stone-100">
                                {{ $textoEstado }}
                            </p>

                        </div>

                    </div>

                    {{-- ACCIONES --}}
                    <div class="mt-5 flex flex-wrap gap-2">

                        {{-- VER HISTORIAL --}}
                        <a
                            href="{{ route('clientes.pagos', $cliente->id) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-orange-500 px-3 py-2 text-xs font-black text-white hover:bg-orange-600 transition"
                        >
                            💳 Ver historial
                        </a>

                        {{-- WHATSAPP --}}
                        <a
                            href="https://wa.me/549{{ $telefono }}?text={{ urlencode('Hola ' . $cliente->nombre . ', te recordamos el vencimiento de tu cuota del gimnasio.') }}"
                            target="_blank"
                            style="background:black;color:white;border-radius:14px;padding:8px 12px;font-size:12px;font-weight:bold;display:inline-flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;"
                        >

                            <img
                                src="{{ asset('images/whatsapp.png') }}"
                                alt="WhatsApp"
                                style="width:18px; height:18px; object-fit:contain; display:block;"
                            >

                            Avisar vencimiento

                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</x-layouts::app>