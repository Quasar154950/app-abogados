<x-layouts::app :title="__('Tareas del Caso')">

    <div class="space-y-6">

        {{-- CABECERA --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            <h1 class="text-2xl font-bold italic font-sans">Gestión de Tareas</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 font-sans">
                Gestiona y filtra las tareas, recordatorios y estados vinculados a tus clientes.
            </p>
        </div>

        @if(session('success'))
            <x-alert-success>
                {{ session('success') }}
            </x-alert-success>
        @endif

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            {{-- MOTOR DE FILTRADO COMBINADO --}}
            <form method="GET" action="{{ route('seguimientos.index') }}" class="mb-8 grid grid-cols-1 gap-3 w-full">

                {{-- Filtro Cliente --}}
                <div>
                    <select name="cliente_id" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Todos los Clientes</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ ($filtros['cliente_id'] ?? '') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro Estado --}}
                <div>
                    <select name="estado" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Cualquier Estado</option>
                        <option value="pendiente" {{ ($filtros['estado'] ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_curso" {{ ($filtros['estado'] ?? '') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                        <option value="resuelto" {{ ($filtros['estado'] ?? '') == 'resuelto' ? 'selected' : '' }}>Completada</option>
                    </select>
                </div>

                {{-- Filtro Prioridad --}}
                <div>
                    <select name="prioridad" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Cualquier Prioridad</option>
                        <option value="baja" {{ ($filtros['prioridad'] ?? '') == 'baja' ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ ($filtros['prioridad'] ?? '') == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta" {{ ($filtros['prioridad'] ?? '') == 'alta' ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>

                {{-- Filtro Etiqueta --}}
                <div>
                    <select name="etiqueta_id" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Todas las Etiquetas</option>
                        @foreach($etiquetas as $etiqueta)
                            <option value="{{ $etiqueta->id }}" {{ ($filtros['etiqueta_id'] ?? '') == $etiqueta->id ? 'selected' : '' }}>
                                {{ $etiqueta->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro Expediente --}}
                <div>
                    <select name="expediente_id" class="w-full rounded-lg border border-neutral-300 py-2 px-3 dark:bg-neutral-900 dark:border-neutral-700 text-sm outline-none focus:ring-2 focus:ring-blue-500 font-sans text-neutral-600 dark:text-neutral-300">
                        <option value="">Todos los Expedientes</option>
                        @foreach($expedientes as $expediente)
                            <option value="{{ $expediente->id }}" {{ ($filtros['expediente_id'] ?? '') == $expediente->id ? 'selected' : '' }}>
                                {{ $expediente->caratula }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones de acción --}}
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-bold hover:bg-blue-700 transition shadow-sm cursor-pointer font-sans">
                        🔍 Filtrar
                    </button>

                    <a href="{{ route('seguimientos.index') }}" class="bg-neutral-200 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 rounded-lg px-3 py-2 text-sm hover:bg-neutral-300 dark:hover:bg-neutral-700 transition flex items-center justify-center shadow-sm">
                        🧹
                    </a>
                </div>
            </form>

            <livewire:seguimientos-tabla />

        </div>
    </div>
</x-layouts::app>