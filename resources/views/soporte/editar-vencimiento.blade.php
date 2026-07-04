<x-layouts::app :title="'Editar suscripción'">

    <div class="max-w-xl mx-auto space-y-5">

        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">
            <h1 class="text-2xl font-bold">
                ✏️ Editar suscripción
            </h1>

            <p class="text-sm text-gray-500 mt-2">
                {{ $user->email }}
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">

            <form method="POST"
                  action="{{ route('soporte.guardar.vencimiento', $user) }}"
                  class="space-y-5">

                @csrf

                {{-- Fecha de vencimiento --}}
                <div>
                    <label class="text-sm font-semibold">
                        Fecha de vencimiento
                    </label>

                    <input
                        type="date"
                        name="fecha_vencimiento"
                        value="{{ optional($user->fecha_vencimiento)->format('Y-m-d') }}"
                        class="w-full mt-2 border rounded-lg px-3 py-2">
                </div>

                {{-- Plan --}}
                <div>
                    <label class="text-sm font-semibold">
                        Plan
                    </label>

                    <select
                        name="plan"
                        class="w-full mt-2 border rounded-lg px-3 py-2">

                        <option value="">Sin plan</option>

                        <option value="Básico"
                            {{ $user->plan == 'Básico' ? 'selected' : '' }}>
                            Básico
                        </option>

                        <option value="Pro"
                            {{ $user->plan == 'Pro' ? 'selected' : '' }}>
                            Pro
                        </option>

                    </select>
                </div>

                {{-- Precio --}}
                <div>
                    <label class="text-sm font-semibold">
                        Precio mensual ($)
                    </label>

                    <input
                        type="number"
                        name="precio_suscripcion"
                        min="0"
                        step="0.01"
                        value="{{ $user->precio_suscripcion }}"
                        class="w-full mt-2 border rounded-lg px-3 py-2">
                </div>

                <button
                    class="px-5 py-2 rounded bg-green-600 hover:bg-green-700 text-white transition">
                    💾 Guardar cambios
                </button>

            </form>

        </div>

    </div>

</x-layouts::app>


