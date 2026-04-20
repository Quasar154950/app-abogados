<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-zinc-100">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-md p-6">

            <h1 class="text-2xl font-bold text-zinc-800 mb-2">
                Bienvenido, {{ auth()->user()->name }}
            </h1>

            <p class="text-zinc-600 mb-6">
                Este es tu panel de cliente.
            </p>

            <div class="grid grid-cols-1 gap-4">

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-zinc-500">Nombre</p>
                    <p class="font-semibold text-zinc-800">
                        {{ auth()->user()->name }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-zinc-500">Email</p>
                    <p class="font-semibold text-zinc-800">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-zinc-500">Rol</p>
                    <p class="font-semibold text-zinc-800">
                        {{ auth()->user()->role }}
                    </p>
                </div>

            </div>

            <div class="mt-6">
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
