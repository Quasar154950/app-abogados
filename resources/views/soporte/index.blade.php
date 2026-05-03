<x-layouts::app :title="'Panel de Soporte'">

    <div class="space-y-5 text-left">

        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">
            <h1 class="text-2xl font-bold italic">Panel de Soporte</h1>
            <p class="text-sm text-neutral-600 mt-2">
                Administración de estudios y suscripciones
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">
            <h2 class="text-lg font-bold mb-4">Estudios / Abogados</h2>

            @php
                $users = \App\Models\User::where('role', 'abogado')
                    ->where('email', '!=', 'soporte@tuempresa.com')
                    ->orderBy('email')
                    ->get();
            @endphp

            <div class="space-y-2">
                @foreach($users as $user)
                    @php
                        $vencido = $user->fecha_vencimiento && now()->greaterThan($user->fecha_vencimiento);

                        $diasRestantes = $user->fecha_vencimiento
                            ? max(0, now()->startOfDay()->diffInDays($user->fecha_vencimiento->startOfDay(), false))
                            : null;

                        if (!$user->activo) {
                            $estado = 'Inactivo';
                        } elseif ($vencido) {
                            $estado = 'Vencido';
                        } else {
                            $estado = 'Vigente';
                        }
                    @endphp

                    <div class="p-3 border rounded-lg flex justify-between items-center gap-4">
                        <div>
                            <div class="font-bold">{{ $user->email }}</div>

                            <div class="text-xs text-gray-500">
                                Vence:
                                {{ $user->fecha_vencimiento ? $user->fecha_vencimiento->format('d/m/Y') : 'Sin fecha' }}
                            </div>

                            <div class="text-xs text-gray-500">
                                Días restantes:
                                {{ is_null($diasRestantes) ? 'Sin fecha' : $diasRestantes }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">

                            <div class="text-sm font-bold">
                                {{ $estado }}
                            </div>

                            {{-- BOTÓN RENOVAR --}}
                            <form method="POST" action="{{ route('renovar.suscripcion', $user) }}">
                                @csrf
                                <button
                                    onclick="return confirm('¿Seguro querés renovar 30 días?')"
                                    class="text-xs px-3 py-1 rounded bg-green-600 text-white">
                                    Renovar
                                </button>
                            </form>

                            {{-- BOTÓN SUSPENDER / ACTIVAR --}}
                            <form method="POST" action="{{ route('toggle.activo', $user) }}">
                                @csrf
                                <button
                                    onclick="return confirm('¿Seguro querés cambiar el estado?')"
                                    class="text-xs px-3 py-1 rounded
                                        {{ $user->activo ? 'bg-red-600' : 'bg-blue-600' }} text-white">
                                    {{ $user->activo ? 'Suspender' : 'Activar' }}
                                </button>
                            </form>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</x-layouts::app>