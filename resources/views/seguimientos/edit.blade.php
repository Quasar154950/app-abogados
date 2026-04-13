<x-layouts::app :title="$seguimiento->expediente ? 'Editar Tarea del Expediente' : 'Editar Tarea del Cliente'">

    <div class="space-y-6 pb-10 text-left">

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            
            @if($seguimiento->expediente)
                <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">
                    Editar Tarea del Expediente
                </h1>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                    Modificá la tarea asociada al expediente "{{ $seguimiento->expediente->caratula }}".
                </p>
            @else
                <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">
                    Editar Tarea del Cliente
                </h1>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                    Modificá la tarea general del cliente "{{ $seguimiento->cliente->nombre }}".
                </p>
            @endif

        </div>

        @if(session('success'))
            <x-alert-success>{{ session('success') }}</x-alert-success>
        @endif

        <x-alert-error />

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            <form method="POST" action="{{ route('seguimientos.update', $seguimiento->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="descripcion" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Detalle de la tarea</label>
                    <textarea
                        name="descripcion"
                        id="descripcion"
                        rows="4"
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-green-500 outline-none text-sm"
                    >{{ old('descripcion', $seguimiento->descripcion) }}</textarea>
                    @error('descripcion') <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Estado --}}
                    <div>
                        <label for="estado" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Estado</label>
                        <select
                            name="estado"
                            id="estado"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 text-sm cursor-pointer"
                        >
                            <option value="pendiente" {{ old('estado', $seguimiento->estado) === 'pendiente' ? 'selected' : '' }}>🟡 Pendiente</option>
                            <option value="en_curso" {{ old('estado', $seguimiento->estado) === 'en_curso' ? 'selected' : '' }}>🔵 En curso</option>
                            <option value="resuelto" {{ old('estado', $seguimiento->estado) === 'resuelto' ? 'selected' : '' }}>🟢 Completada</option>
                        </select>
                    </div>

                    {{-- Prioridad --}}
                    <div>
                        <label for="prioridad" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Prioridad</label>
                        <select
                            name="prioridad"
                            id="prioridad"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 text-sm cursor-pointer"
                        >
                            <option value="baja" {{ old('prioridad', $seguimiento->prioridad) === 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('prioridad', $seguimiento->prioridad) === 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('prioridad', $seguimiento->prioridad) === 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    {{-- Etiqueta --}}
                    <div>
                        <label for="etiqueta_id" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Etiqueta</label>
                        <select
                            name="etiqueta_id"
                            id="etiqueta_id"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 text-sm cursor-pointer font-bold"
                        >
                            <option value="">Sin etiqueta</option>
                            @foreach($etiquetas as $et)
                                <option value="{{ $et->id }}" 
                                    style="color: {{ $et->color }};" 
                                    {{ old('etiqueta_id', $seguimiento->etiqueta_id) == $et->id ? 'selected' : '' }}>
                                    ● {{ $et->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="fecha_recordatorio" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">Fecha límite</label>
                        <input
                            type="date"
                            name="fecha_recordatorio"
                            id="fecha_recordatorio"
                            value="{{ old('fecha_recordatorio', $seguimiento->fecha_recordatorio) }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 text-sm cursor-pointer"
                        >
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="flex gap-2 pt-6 border-t border-neutral-100 dark:border-neutral-800">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700 transition font-bold shadow-sm cursor-pointer active:scale-[0.98]"
                    >
                        ✔ Guardar cambios
                    </button>

                    <a
                        href="{{ route('clientes.show', $seguimiento->cliente_id) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-neutral-200 dark:bg-neutral-800 px-4 py-2 text-sm text-neutral-900 dark:text-neutral-100 hover:bg-neutral-300 transition border border-neutral-300 dark:border-neutral-700 font-bold shadow-sm cursor-pointer active:scale-[0.98]"
                    >
                        ← Volver
                    </a>
                </div>

            </form>

        </div>

    </div>

</x-layouts::app>