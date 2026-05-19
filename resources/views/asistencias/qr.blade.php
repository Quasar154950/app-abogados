<x-layouts::app :title="'QR de ' . $cliente->nombre">

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- CABECERA --}}
        <div class="rounded-xl border border-stone-300 bg-stone-200 p-6 shadow-sm text-center">

            <h1 class="text-2xl font-bold text-stone-900">
                📱 QR de Acceso
            </h1>

            <p class="mt-2 text-stone-600">
                Socio:
                <span class="font-bold">
                    {{ $cliente->nombre }}
                </span>
            </p>

        </div>

        {{-- QR --}}
        <div class="rounded-xl border border-stone-300 bg-stone-200 p-8 shadow-sm">

            <div class="flex justify-center">

                <div class="rounded-xl bg-white p-6 shadow-sm border border-stone-300">

                    {!! $qrSvg !!}

                </div>

            </div>

            <div class="mt-6 text-center">

                <p class="text-sm text-stone-600">
                    Escaneá este código para registrar ingreso o salida.
                </p>

            </div>

            {{-- DATOS DEL SOCIO --}}
            <div class="mt-6 rounded-xl border border-stone-300 bg-stone-100 p-4 text-center">

                <p class="text-sm text-stone-500 font-bold uppercase">
                    Socio
                </p>

                <p class="mt-1 text-xl font-bold text-stone-900">
                    {{ $cliente->nombre }}
                </p>

                <p class="mt-3 text-sm text-stone-500 font-bold uppercase">
                    Vencimiento de cuota
                </p>

                <p class="mt-1 text-lg font-bold text-stone-900">
                    @if($cliente->fecha_vencimiento_cuota)
                        {{ \Carbon\Carbon::parse($cliente->fecha_vencimiento_cuota)->format('d/m/Y') }}
                    @else
                        Sin vencimiento cargado
                    @endif
                </p>

            </div>

        </div>

        {{-- BOTONES --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">

            <a
                href="{{ route('clientes.show', $cliente->id) }}"
                style="background:black;color:white;border-radius:14px;padding:10px 16px;font-size:14px;font-weight:bold;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;"
            >
                ← Volver al socio
            </a>

            <form
                method="POST"
                action="{{ route('asistencias.marcar', $cliente->id) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="inline-flex items-center justify-center whitespace-nowrap bg-orange-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-orange-600 transition shadow-sm cursor-pointer active:scale-95"
                >
                    👥 Marcar asistencia manual
                </button>

            </form>

        </div>

    </div>

</x-layouts::app>