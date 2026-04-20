<x-layouts::app>
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                <h1 class="text-2xl font-bold text-zinc-800 dark:text-white mb-2">
                    Bienvenido, {{ auth()->user()->name }}
                </h1>

                <p class="text-zinc-600 dark:text-zinc-300 mb-6">
                    Este es tu panel de cliente.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Nombre</p>
                        <p class="text-base font-semibold text-zinc-800 dark:text-white">
                            {{ auth()->user()->name }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Email</p>
                        <p class="text-base font-semibold text-zinc-800 dark:text-white">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Rol</p>
                        <p class="text-base font-semibold text-zinc-800 dark:text-white">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-white font-medium hover:bg-red-700 transition">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
