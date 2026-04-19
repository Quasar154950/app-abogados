<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Cliente</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-100 text-neutral-800">

    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="bg-white border border-neutral-200 rounded-2xl shadow-sm p-6">
            <h1 class="text-3xl font-bold text-neutral-900">Panel del Cliente</h1>
            <p class="mt-2 text-sm text-neutral-500">
                Bienvenido a tu espacio personal.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <div class="bg-white border border-neutral-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-4">Datos de acceso</h2>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-neutral-500">Nombre</span>
                        <span class="font-medium text-neutral-800">{{ auth()->user()->name }}</span>
                    </div>

                    <div>
                        <span class="block text-neutral-500">Email</span>
                        <span class="font-medium text-neutral-800">{{ auth()->user()->email }}</span>
                    </div>

                    <div>
                        <span class="block text-neutral-500">Rol</span>
                        <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1 mt-1">
                            Cliente
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-4">Próximamente</h2>

                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="rounded-xl bg-neutral-50 border border-neutral-200 px-4 py-3">
                        📁 Ver mis expedientes
                    </li>
                    <li class="rounded-xl bg-neutral-50 border border-neutral-200 px-4 py-3">
                        📄 Descargar ficha del expediente
                    </li>
                    <li class="rounded-xl bg-neutral-50 border border-neutral-200 px-4 py-3">
                        💬 Contactar por WhatsApp
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-6 bg-white border border-neutral-200 rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-neutral-900 mb-3">Estado del módulo cliente</h2>
            <p class="text-sm text-neutral-600">
                El acceso del cliente ya está funcionando correctamente. En los próximos pasos se podrán agregar
                expedientes, descargas de PDF y contacto directo.
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Cerrar sesión
                </button>
            </form>
        </div>

    </div>

</body>
</html>