<x-layouts::app :title="'Asistencias'">

    <div class="space-y-6">

        {{-- CABECERA --}}
        <div class="rounded-xl border border-stone-300 bg-stone-200 p-6 shadow-sm">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>

                    <h1 class="text-2xl font-bold text-stone-900">
                        👥 Control de Asistencia
                    </h1>

                    <p class="mt-2 text-sm text-stone-600">
                        Ingresos, egresos y socios presentes en el gimnasio.
                    </p>

                </div>

                <div class="rounded-xl bg-orange-500 px-4 py-3 text-white shadow-sm">

                    <p class="text-xs uppercase font-bold opacity-80">
                        Presentes ahora
                    </p>

                    <p class="text-3xl font-black">
                        {{ $presentes->count() }}
                    </p>

                </div>

            </div>

        </div>

        {{-- PRESENTES --}}
        <div class="rounded-xl border border-stone-300 bg-stone-200 p-6 shadow-sm">

            <h2 class="text-xl font-bold text-stone-900 mb-4">
                🟢 Socios presentes
            </h2>

            @if($presentes->isEmpty())

                <div class="rounded-xl bg-stone-100 border border-stone-300 p-6 text-center">

                    <p class="text-stone-500 italic">
                        No hay socios presentes actualmente.
                    </p>

                </div>

            @else

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                    @foreach($presentes as $asistencia)

                        <div class="rounded-xl border border-stone-300 bg-stone-100 p-4 shadow-sm">

                            <div class="flex items-start justify-between gap-3">

                                <div>

                                    <h3 class="text-lg font-bold text-stone-900">
                                        {{ $asistencia->cliente->nombre }}
                                    </h3>

                                    <p class="text-sm text-stone-500 mt-1">
                                        🕒 Ingreso:
                                        {{ $asistencia->hora_ingreso->format('H:i') }} hs
                                    </p>

                                </div>

                                <form method="POST"
                                      action="{{ route('asistencias.salida', $asistencia->id) }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        style="background:black;color:white;border-radius:12px;padding:8px 12px;font-size:12px;font-weight:bold;"
                                    >
                                        Salida
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

        {{-- INGRESOS DEL DÍA --}}
        <div class="rounded-xl border border-stone-300 bg-stone-200 p-6 shadow-sm">

            <h2 class="text-xl font-bold text-stone-900 mb-4">
                📋 Ingresos de hoy
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-stone-300 text-left text-sm text-stone-500">

                            <th class="pb-3 font-bold">Socio</th>

                            <th class="pb-3 font-bold">Ingreso</th>

                            <th class="pb-3 font-bold">Salida</th>

                            <th class="pb-3 font-bold">Estado</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($ingresosHoy as $item)

                            <tr class="border-b border-stone-300">

                                <td class="py-3 text-stone-900 font-semibold">
                                    {{ $item->cliente->nombre }}
                                </td>

                                <td class="py-3 text-stone-700">
                                    {{ $item->hora_ingreso->format('H:i') }} hs
                                </td>

                                <td class="py-3 text-stone-700">

                                    @if($item->hora_salida)
                                        {{ $item->hora_salida->format('H:i') }} hs
                                    @else
                                        —
                                    @endif

                                </td>

                                <td class="py-3">

                                    @if($item->presente)

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                            Presente
                                        </span>

                                    @else

                                        <span class="rounded-full bg-stone-300 px-3 py-1 text-xs font-bold text-stone-700">
                                            Retirado
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="py-6 text-center text-stone-500 italic">
                                    No hay asistencias registradas hoy.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-layouts::app>