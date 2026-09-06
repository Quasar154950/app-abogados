<x-layouts::app :title="'Panel de Soporte'">

    <div class="space-y-5 text-left">

        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">
            <h1 class="text-2xl font-bold italic">Panel de Soporte</h1>
            <p class="text-sm text-neutral-600 mt-2">
                Administración de estudios y suscripciones de Fernando García
            </p>
        </div>

        {{-- 🔑 MENSAJE PASSWORD GENERADA --}}
        @if(session('password_generada'))
            <div class="p-3 rounded bg-blue-100 text-blue-800 text-sm font-bold">
                Nueva contraseña: {{ session('password_generada') }}
            </div>
        @endif

        {{-- ✅ MENSAJE DE ÉXITO --}}
        @if(session('success'))
            <div class="p-3 rounded bg-green-100 text-green-800 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        {{-- MÉTRICAS --}}
        @php
            $estudios = \App\Models\Estudio::with([
                'abogados' => function ($query) {
                    $query->where('role', 'abogado')
                        ->orderBy('name');
                }
            ])->orderBy('nombre')->get();

            $totalEstudios = $estudios->count();
            $activos = $estudios->where('activo', true)->count();
            $inactivos = $estudios->where('activo', false)->count();

            $vencidos = $estudios->filter(function ($estudio) {
                return $estudio->fecha_vencimiento
                    && now()->greaterThan($estudio->fecha_vencimiento);
            })->count();

            $porVencer = $estudios->filter(function ($estudio) {
                if (!$estudio->fecha_vencimiento) {
                    return false;
                }

                $dias = now()->startOfDay()->diffInDays(
                    $estudio->fecha_vencimiento->copy()->startOfDay(),
                    false
                );

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

        {{-- LISTADO DE ESTUDIOS --}}
        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">

            <h2 class="text-lg font-bold mb-4">Estudios</h2>

            <div class="space-y-4">

                @forelse($estudios as $estudio)

                    @php
                        $vencido = $estudio->fecha_vencimiento
                            && now()->greaterThan($estudio->fecha_vencimiento);

                        $diasRestantes = $estudio->fecha_vencimiento
                            ? max(
                                0,
                                now()->startOfDay()->diffInDays(
                                    $estudio->fecha_vencimiento->copy()->startOfDay(),
                                    false
                                )
                            )
                            : null;

                        if (!$estudio->activo) {
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

                    <div class="p-4 border rounded-xl space-y-4 bg-white">

                        {{-- INFORMACIÓN DEL ESTUDIO --}}
                        <div>
                            <div class="text-lg font-bold">
                                {{ $estudio->nombre }}
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                Acceso: /estudio/{{ $estudio->slug }}
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                Vence:
                                {{ $estudio->fecha_vencimiento
                                    ? $estudio->fecha_vencimiento->format('d/m/Y')
                                    : 'Sin fecha' }}
                            </div>

                            <div class="text-xs font-bold mt-1" style="color: {{ $color }}">
                                Días restantes:
                                {{ is_null($diasRestantes) ? 'Sin fecha' : $diasRestantes }}
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                Plan: {{ strtoupper($estudio->plan ?? 'Sin plan') }}
                                · Precio:
                                ${{ number_format($estudio->precio_suscripcion ?? 0, 0, ',', '.') }}
                            </div>

                            <div class="text-sm font-bold mt-2">
                                {{ $estado }}
                            </div>
                        </div>

                        {{-- ACCIONES DEL ESTUDIO --}}
                        <div class="flex gap-2 overflow-x-auto whitespace-nowrap pb-1">

                            <form method="POST"
                                  action="{{ route('renovar.suscripcion', $estudio) }}"
                                  class="shrink-0">
                                @csrf

                                <button
                                    onclick="return confirm('¿Seguro querés renovar 30 días al estudio completo?')"
                                    class="text-sm px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white cursor-pointer transition">
                                    🔄 Renovar
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('toggle.activo', $estudio) }}"
                                  class="shrink-0">
                                @csrf

                                <button
                                    onclick="return confirm('¿Seguro querés cambiar el estado del estudio completo?')"
                                    class="text-sm px-4 py-2 rounded {{ $estudio->activo ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white cursor-pointer transition">
                                    {{ $estudio->activo ? '⛔ Suspender' : '✅ Activar' }}
                                </button>
                            </form>

                            <a href="{{ route('soporte.editar.vencimiento', $estudio) }}"
                               class="shrink-0 text-sm px-4 py-2 rounded bg-yellow-500 hover:bg-yellow-600 text-white transition">
                                ✏️ Editar suscripción
                            </a>

                            <button
                                type="button"
                                onclick="navigator.clipboard.writeText('{{ url('/estudio/' . $estudio->slug) }}')"
                                class="shrink-0 text-sm px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer transition">
                                📩 Copiar acceso
                            </button>

                        </div>

                        {{-- ABOGADOS DEL ESTUDIO --}}
                        <div class="border-t pt-4">

                            <div class="text-sm font-bold mb-3">
                                Abogados ({{ $estudio->abogados->count() }})
                            </div>

                            <div class="space-y-2">

                                @forelse($estudio->abogados as $user)

                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-3 bg-gray-50 rounded-lg">

                                        <div>
                                            <div class="font-semibold">
                                                {{ $user->name }}
                                            </div>

                                            <div class="text-xs text-gray-500">
                                                {{ $user->email }}
                                            </div>
                                        </div>

                                        <div class="flex gap-2 overflow-x-auto whitespace-nowrap">

                                            <form method="POST"
                                                  action="{{ route('soporte.reset.password', $user) }}"
                                                  class="shrink-0">
                                                @csrf

                                                <button
                                                    onclick="return confirm('¿Resetear contraseña de este abogado?')"
                                                    class="text-sm px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white cursor-pointer transition">
                                                    🔑 Reset
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('soporte.ver-como', $user) }}"
                                                  class="shrink-0">
                                                @csrf

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('¿Entrar como este abogado?')"
                                                    class="text-sm px-3 py-2 rounded bg-violet-600 hover:bg-violet-700 text-white cursor-pointer transition">
                                                    👁 Ver usuario
                                                </button>
                                            </form>

                                        </div>

                                    </div>

                                @empty

                                    <div class="text-sm text-gray-500">
                                        Este estudio todavía no tiene abogados.
                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-sm text-gray-500">
                        No hay estudios registrados.
                    </div>

                @endforelse

            </div>
        </div>

        {{-- BACKUP GLOBAL --}}
        <div class="rounded-xl border border-neutral-200 p-5 bg-white shadow-sm">

            <form method="POST" action="{{ route('soporte.backup') }}">
                @csrf

                <button
                    type="submit"
                    onclick="return confirm('¿Generar backup del sistema?')"
                    class="text-sm px-4 py-2 rounded bg-cyan-600 hover:bg-cyan-700 text-white cursor-pointer transition">
                    💾 Backup del sistema
                </button>
            </form>

        </div>

    </div>

</x-layouts::app>


