<x-layouts::app :title="'Historial de pagos'">

    <div class="space-y-6">

        {{-- ENCABEZADO --}}
        <div class="rounded-xl border border-stone-300 p-6 bg-stone-200 shadow-sm">

            <h1 class="text-2xl font-bold text-stone-900">
                💰 Historial de pagos
            </h1>

            <p class="mt-2 text-sm text-stone-600">
                Socio:
                <span class="font-bold">
                    {{ $cliente->nombre }}
                </span>
            </p>

        </div>

        {{-- TABLA --}}
        <div class="rounded-xl border border-stone-300 bg-stone-200 shadow-sm overflow-x-auto">

            @if($pagos->isEmpty())

                <div class="p-10 text-center text-stone-500 italic">
                    No hay pagos registrados todavía.
                </div>

            @else

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="border-b border-stone-300 text-stone-500 text-xs uppercase">

                            <th class="p-4 text-left">Fecha</th>

                            <th class="p-4 text-left">Monto</th>

                            <th class="p-4 text-left">Método</th>

                            <th class="p-4 text-left">Observación</th>

                            <th class="p-4 text-left">Vence</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-stone-300">

                        @foreach($pagos as $pago)

                            <tr class="hover:bg-stone-100 transition">

                                <td class="p-4 text-sm text-stone-700">
                                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                </td>

                                <td class="p-4 text-sm font-bold text-green-600">
                                    ${{ number_format($pago->monto ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="p-4 text-sm text-stone-700">
                                    {{ $pago->metodo_pago }}
                                </td>

                                <td class="p-4 text-sm text-stone-700">
                                    {{ $pago->observacion }}
                                </td>

                                <td class="p-4 text-sm text-stone-700">
                                    {{ \Carbon\Carbon::parse($pago->vencimiento_cuota)->format('d/m/Y') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @endif

        </div>

    </div>

</x-layouts::app>
