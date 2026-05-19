<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de cuota</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-zinc-100">

    @php

        $fechaVencimiento = null;
        $diasRestantes = null;

        if ($cliente && $cliente->fecha_vencimiento_cuota) {

            $fechaVencimiento = \Carbon\Carbon::parse($cliente->fecha_vencimiento_cuota);

            $diasRestantes = now()->diffInDays($fechaVencimiento, false);
        }

    @endphp

    <div class="min-h-screen p-6">

        <div class="w-full max-w-3xl mx-auto">

            {{-- CABECERA --}}
            <div class="bg-stone-200 border border-stone-300 rounded-xl shadow-md p-6 mb-6">

                <h1 class="text-2xl font-black text-zinc-800">
                    💳 Estado de cuota
                </h1>

                <p class="text-sm text-zinc-600 mt-2">
                    Información sobre tu membresía del gimnasio.
                </p>

            </div>

            {{-- ESTADO --}}
            <div class="bg-stone-200 border border-stone-300 rounded-xl shadow-md p-6 mb-6">

                @if($cliente && $fechaVencimiento)

                    @if($diasRestantes < 0)

                        <div class="inline-flex items-center rounded-xl bg-red-100 px-4 py-3 text-base font-black text-red-700">
                            ❌ Cuota vencida
                        </div>

                    @elseif($diasRestantes <= 5)

                        <div class="inline-flex items-center rounded-xl bg-yellow-100 px-4 py-3 text-base font-black text-yellow-700">
                            ⚠️ Tu cuota vence pronto
                        </div>

                    @else

                        <div class="inline-flex items-center rounded-xl bg-green-100 px-4 py-3 text-base font-black text-green-700">
                            ✅ Cuota al día
                        </div>

                    @endif

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="border border-stone-300 bg-stone-100 rounded-xl p-4">

                            <p class="text-sm text-zinc-500">
                                Socio
                            </p>

                            <p class="font-bold text-zinc-800 mt-1">
                                {{ $cliente->nombre }}
                            </p>

                        </div>

                        <div class="border border-stone-300 bg-stone-100 rounded-xl p-4">

                            <p class="text-sm text-zinc-500">
                                Vencimiento
                            </p>

                            <p class="font-bold text-zinc-800 mt-1">
                                {{ $fechaVencimiento->format('d/m/Y') }}
                            </p>

                        </div>

                    </div>

                @else

                    <div class="rounded-xl bg-yellow-100 text-yellow-800 font-bold px-4 py-3">
                        ⚠️ No hay vencimiento cargado.
                    </div>

                @endif

            </div>

            {{-- FUTURO PAGO ONLINE --}}
            <div class="bg-stone-200 border border-stone-300 rounded-xl shadow-md p-6 mb-6">

                <h2 class="text-lg font-black text-zinc-800">
                    🚀 Próximamente
                </h2>

                <p class="text-sm text-zinc-600 mt-3 leading-relaxed">
                    Muy pronto vas a poder:
                </p>

                <ul class="mt-4 space-y-2 text-sm text-zinc-700">

                    <li>✅ Pagar tu cuota online</li>
                    <li>✅ Ver historial de pagos</li>
                    <li>✅ Descargar comprobantes</li>
                    <li>✅ Abonar con Mercado Pago</li>

                </ul>

            </div>

            {{-- CONTACTO --}}
            <div class="bg-stone-200 border border-stone-300 rounded-xl shadow-md p-6 mb-6">

                <h2 class="text-lg font-black text-zinc-800">
                    📲 Administración
                </h2>

                <p class="text-sm text-zinc-600 mt-3">
                    Si necesitás regularizar tu cuota o tenés dudas, comunicate con el gimnasio.
                </p>

                <a
                    href="https://wa.me/"
                    target="_blank"
                    class="mt-5 inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600 transition"
                >
                    WhatsApp administración
                </a>

            </div>

            {{-- VOLVER --}}
            <div class="text-center">

                <a
                    href="{{ route('cliente.dashboard') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-black px-5 py-3 text-sm font-bold text-white hover:bg-zinc-800 transition"
                >
                    ← Volver al panel
                </a>

            </div>

        </div>

    </div>

</body>
</html>
