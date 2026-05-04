<x-layouts::app :title="'Panel de Soporte'">

    <div class="space-y-5 text-left">

        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">
            <h1 class="text-2xl font-bold italic">Panel de Soporte</h1>
            <p class="text-sm text-neutral-600 mt-2">
                Administración de estudios y suscripciones
            </p>
        </div>

        {{-- 🔑 MENSAJE PASSWORD GENERADA --}}
        @if(session('password_generada'))
            <div class="p-3 rounded bg-blue-100 text-blue-800 text-sm font-bold">
                Nueva contraseña: {{ session('password_generada') }}
            </div>
        @endif

        {{-- MÉTRICAS --}}
        @php
            $users = \App\Models\User::where('role', 'abogado')
                ->where('email', '!=', 'soporte@tuempresa.com')
                ->orderBy('email')
                ->get();

            $totalEstudios = $users->count();
            $activos = $users->where('activo', true)->count();
            $inactivos = $users->where('activo', false)->count();

            $vencidos = $users->filter(fn ($u) => $u->fecha_vencimiento && now()->greaterThan($u->fecha_vencimiento))->count();

            $porVencer = $users->filter(function ($u) {
                if (!$u->fecha_vencimiento) return false;
                $dias = max(0, now()->startOfDay()->diffInDays($u->fecha_vencimiento->startOfDay(), false));
                return $dias > 0 && $dias <= 7;
            })->count();
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">

            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-center">
                <div class="text-xs text-blue-600">Total</div>
                <div class="text-xl font-bold text-blue-800">{{ $totalEstudios }}</div>
            </div>

            <div class="p-3 rounded-xl bg-green-50 border border-green-200 text-center">
                <div class="text-xs text-green-600">Activos</div>
                <div class="text-xl font-bold text-green-800">{{ $activos }}</div>
            </div>

            <div class="p-3 rounded-xl bg-gray-100 border border-gray-300 text-center">
                <div class="text-xs text-gray-600">Inactivos</div>
                <div class="text-xl font-bold text-gray-800">{{ $inactivos }}</div>
            </div>

            <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-center">
                <div class="text-xs text-red-600">Vencidos</div>
                <div class="text-xl font-bold text-red-800">{{ $vencidos }}</div>
            </div>

            <div class="p-3 rounded-xl bg-yellow-50 border border-yellow-200 text-center">
                <div class="text-xs text-yellow-600">Por vencer</div>
                <div class="text-xl font-bold text-yellow-800">{{ $porVencer }}</div>
            </div>

        </div>

        {{-- LISTADO --}}
        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">
            <h2 class="text-lg font-bold mb-4">Estudios / Abogados</h2>

            <div class="space-y-3">
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

                        if (is_null($diasRestantes)) {
                            $color = '#6b7280';
                        } elseif ($diasRestantes > 10) {
                            $color = '#16a34a';
                        } elseif ($diasRestantes > 3) {
                            $color = '#ca8a04';
                        } else {
                            $color = '#dc2626';
                        }
                    @endphp

                    <div class="p-4 border rounded-xl space-y-3 bg-white">

                        {{-- INFO --}}
                        <div>
                            <div class="font-semibold">{{ $user->email }}</div>

                            <div class="text-xs text-gray-500">
                                Vence:
                                {{ $user->fecha_vencimiento ? $user->fecha_vencimiento->format('d/m/Y') : 'Sin fecha' }}
                            </div>

                            <div class="text-xs font-bold" style="color: {{ $color }}">
                                Días restantes:
                                {{ is_null($diasRestantes) ? 'Sin fecha' : $diasRestantes }}
                            </div>
                        </div>

                        {{-- ESTADO --}}
                        <div class="text-sm font-bold">
                            {{ $estado }}
                        </div>

                        {{-- BOTONES --}}
                        <div class="flex flex-wrap gap-2">

                            {{-- RENOVAR --}}
                            <form method="POST" action="{{ route('renovar.suscripcion', $user) }}">
                                @csrf
                                <button onclick="return confirm('¿Seguro querés renovar 30 días?')"
                                    class="text-sm px-4 py-2 rounded bg-green-600 text-white cursor-pointer hover:bg-green-700 transition">
                                    🔄 Renovar
                                </button>
                            </form>

                            {{-- ACTIVAR / SUSPENDER --}}
                            <form method="POST" action="{{ route('toggle.activo', $user) }}">
                                @csrf
                                <button onclick="return confirm('¿Seguro querés cambiar el estado?')"
                                    class="text-sm px-4 py-2 rounded {{ $user->activo ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white cursor-pointer transition">
                                    {{ $user->activo ? '⛔ Suspender' : '✅ Activar' }}
                                </button>
                            </form>

                            {{-- RESET --}}
                            <form method="POST" action="{{ route('soporte.reset.password', $user) }}">
                                @csrf
                                <button onclick="return confirm('¿Resetear contraseña de este usuario?')"
                                    class="text-sm px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white cursor-pointer transition">
                                    🔑 Reset
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>

</x-layouts::app>
