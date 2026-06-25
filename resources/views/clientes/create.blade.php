<x-layouts::app :title="__('Nuevo Cliente')">

    <div class="space-y-6">

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            <h1 class="text-2xl font-bold dark:text-white">Nuevo Cliente</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Completá los datos para agregar un nuevo cliente al sistema.
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            <x-alert-error />

            {{-- Agregamos x-data para controlar el estado del botón al enviar --}}
            <form method="POST" action="{{ route('clientes.store') }}" class="space-y-4" x-data="{ cargando: false }" x-on:submit="cargando = true">
                @csrf

                <div>
                    <label for="nombre" class="block text-sm font-bold text-neutral-700 dark:text-neutral-300">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="{{ old('nombre') }}"
                        class="mt-1 w-full rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-3 py-2 outline-none focus:ring-2 focus:ring-green-500 transition"
                        required
                    >
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-bold text-neutral-700 dark:text-neutral-300">Teléfono</label>
                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        value="{{ old('telefono') }}"
                        class="mt-1 w-full rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-3 py-2 outline-none focus:ring-2 focus:ring-green-500 transition"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-neutral-700 dark:text-neutral-300">Email</label>
                    <input
                        type="text"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        class="mt-1 w-full rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-3 py-2 outline-none focus:ring-2 focus:ring-green-500 transition"
                    >
                </div>

                <div>
                    <label for="direccion" class="block text-sm font-bold text-neutral-700 dark:text-neutral-300">Dirección</label>
                    <input
                        type="text"
                        name="direccion"
                        id="direccion"
                        value="{{ old('direccion') }}"
                        class="mt-1 w-full rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white px-3 py-2 outline-none focus:ring-2 focus:ring-green-500 transition"
                    >
                </div>

                <div class="flex gap-2 pt-2 flex-wrap font-bold">

                    <button
                        type="submit"
                        :disabled="cargando"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700 transition cursor-pointer active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-show="!cargando">✔ Guardar cliente</span>
                        <span x-show="cargando" style="display: none;">⏳ Guardando...</span>
                    </button>

                    <a 
                        href="{{ route('clientes.index') }}" 
                        class="inline-flex items-center gap-2 rounded-lg bg-neutral-300 text-neutral-900 px-4 py-2 text-sm hover:bg-neutral-400 transition cursor-pointer active:scale-95"
                    >
                        ← Volver
                    </a>

                </div>
            </form>

        </div>

    </div>

</x-layouts::app>