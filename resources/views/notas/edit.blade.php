<x-layouts::app :title="__('Editar Nota')">

    <div class="space-y-6">

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            <h1 class="text-2xl font-bold dark:text-white">Editar Nota</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Modificá el contenido de la nota.
            </p>
        </div>

        @if(session('success'))
            <x-alert-success>{{ session('success') }}</x-alert-success>
        @endif

        <x-alert-error />

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            {{-- Activamos Alpine para el relojito --}}
            <form action="{{ route('notas.update', $nota->id) }}" method="POST" class="space-y-4" x-data="{ cargando: false }" x-on:submit="cargando = true">
                @csrf
                @method('PUT')

                <div>
                    <label for="contenido" class="block text-sm font-bold mb-2 text-neutral-700 dark:text-neutral-300">Contenido</label>
                    <textarea
                        name="contenido"
                        id="contenido"
                        rows="6"
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white focus:ring-2 focus:ring-green-500 outline-none transition"
                        required
                    >{{ old('contenido', $nota->contenido) }}</textarea>
                </div>

                <div class="flex items-center gap-3 mt-4 flex-wrap font-bold">

                    <button
                        type="submit"
                        :disabled="cargando"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700 transition cursor-pointer active:scale-95 disabled:opacity-50"
                    >
                        <span x-show="!cargando">✔ Actualizar</span>
                        <span x-show="cargando" style="display: none;">⏳ Actualizando...</span>
                    </button>

                    <a
                        href="{{ route('clientes.show', $nota->cliente_id) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-neutral-300 px-4 py-2 text-sm text-neutral-900 hover:bg-neutral-400 transition cursor-pointer active:scale-95"
                    >
                        ← Cancelar
                    </a>

                </div>

            </form>
        </div>

    </div>

</x-layouts::app>
