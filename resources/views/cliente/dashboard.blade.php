<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-zinc-100">

    @php
        $cliente = \App\Models\Cliente::with('expedientes')
            ->where('user_id', auth()->id())
            ->first();
    @endphp

    <div class="min-h-screen p-6">
        <div class="w-full max-w-5xl mx-auto">

            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold text-zinc-800 mb-2">
                    Bienvenido, {{ $cliente ? $cliente->nombre : auth()->user()->name }}
                </h1>

                <p class="text-zinc-600 mb-6">
                    Este es tu panel de cliente.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="border rounded-lg p-4">
                        <p class="text-sm text-zinc-500">Nombre</p>
                        <p class="font-semibold text-zinc-800">
                            {{ $cliente ? $cliente->nombre : auth()->user()->name }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-4">
                        <p class="text-sm text-zinc-500">Email</p>
                        <p class="font-semibold text-zinc-800">
                            {{ $cliente ? $cliente->email : auth()->user()->email }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-4">
                        <p class="text-sm text-zinc-500">Rol</p>
                        <p class="font-semibold text-zinc-800">
                            {{ auth()->user()->role }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-zinc-800 mb-4">
                    Mis expedientes
                </h2>

                @if (!$cliente)
                    <div class="border border-yellow-300 bg-yellow-50 text-yellow-800 rounded-lg p-4">
                        Tu usuario está logueado, pero todavía no está vinculado a un cliente del sistema.
                    </div>
                @elseif ($cliente->expedientes->isEmpty())
                    <div class="border border-zinc-200 rounded-lg p-4 text-zinc-600">
                        No tenés expedientes cargados por el momento.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($cliente->expedientes as $expediente)
                            <div class="border rounded-lg p-4">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
                                    <h3 class="text-lg font-semibold text-zinc-800">
                                        📁 {{ $expediente->caratula ?: 'Sin carátula' }}
                                    </h3>

                                    <span class="inline-block text-sm bg-purple-100 text-purple-700 px-3 py-1 rounded-full">
                                        {{ $expediente->estado ?: 'Sin estado' }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div class="border rounded-lg p-3">
                                        <p class="text-zinc-500">Número de expediente</p>
                                        <p class="font-medium text-zinc-800">
                                            {{ $expediente->numero_expediente ?: 'No informado' }}
                                        </p>
                                    </div>

                                    <div class="border rounded-lg p-3">
                                        <p class="text-zinc-500">Juzgado</p>
                                        <p class="font-medium text-zinc-800">
                                            {{ $expediente->juzgado ?: 'No informado' }}
                                        </p>
                                    </div>

                                    <div class="border rounded-lg p-3">
                                        <p class="text-zinc-500">Tipo</p>
                                        <p class="font-medium text-zinc-800">
                                            {{ $expediente->tipo ?: 'No informado' }}
                                        </p>
                                    </div>

                                    <div class="border rounded-lg p-3">
                                        <p class="text-zinc-500">Fecha de inicio</p>
                                        <p class="font-medium text-zinc-800">
                                            {{ $expediente->fecha_inicio ? \Carbon\Carbon::parse($expediente->fecha_inicio)->format('d/m/Y') : 'No informada' }}
                                        </p>
                                    </div>
                                </div>

                                @if ($expediente->observaciones)
                                    <div class="mt-3 border rounded-lg p-3">
                                        <p class="text-zinc-500 text-sm">Observaciones</p>
                                        <p class="text-zinc-800">
                                            {{ $expediente->observaciones }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
