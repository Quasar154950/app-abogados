<x-layouts::app :title="__('Editar Expediente')">

    <div class="space-y-6 pb-10 text-left">

        {{-- CABECERA --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">Editar Expediente</h1>
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                Modificá la información del expediente y su estado actual.
            </p>
        </div>

        @if(session('success'))
            <x-alert-success>{{ session('success') }}</x-alert-success>
        @endif

        <x-alert-error />

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900 shadow-sm">

            <form method="POST" action="{{ route('expedientes.update', $expediente->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NÚMERO DE EXPEDIENTE --}}
                    <div>
                        <label for="numero_expediente" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                            Número de expediente
                        </label>
                        <input
                            type="text"
                            name="numero_expediente"
                            id="numero_expediente"
                            value="{{ old('numero_expediente', $expediente->numero_expediente) }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-green-500 outline-none text-sm"
                        >
                        @error('numero_expediente')
                            <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- JUZGADO --}}
                    <div>
                        <label for="juzgado" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                            Juzgado
                        </label>
                        <input
                            type="text"
                            name="juzgado"
                            id="juzgado"
                            value="{{ old('juzgado', $expediente->juzgado) }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-green-500 outline-none text-sm"
                        >
                        @error('juzgado')
                            <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- CARÁTULA --}}
                <div>
                    <label for="caratula" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                        Carátula
                    </label>
                    <input
                        type="text"
                        name="caratula"
                        id="caratula"
                        value="{{ old('caratula', $expediente->caratula) }}"
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-green-500 outline-none text-sm"
                        required
                    >
                    @error('caratula')
                        <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- TIPO --}}
                    <div>
                        <label for="tipo" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                            Tipo
                        </label>
                        <select
                            name="tipo"
                            id="tipo"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 text-sm cursor-pointer"
                        >
                            <option value="">Seleccionar tipo</option>
                            <option value="civil" {{ old('tipo', $expediente->tipo) === 'civil' ? 'selected' : '' }}>Civil</option>
                            <option value="penal" {{ old('tipo', $expediente->tipo) === 'penal' ? 'selected' : '' }}>Penal</option>
                            <option value="laboral" {{ old('tipo', $expediente->tipo) === 'laboral' ? 'selected' : '' }}>Laboral</option>
                            <option value="familia" {{ old('tipo', $expediente->tipo) === 'familia' ? 'selected' : '' }}>Familia</option>
                            <option value="comercial" {{ old('tipo', $expediente->tipo) === 'comercial' ? 'selected' : '' }}>Comercial</option>
                            <option value="administrativo" {{ old('tipo', $expediente->tipo) === 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                        </select>
                        @error('tipo')
                            <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ESTADO --}}
                    <div>
                        <label for="estado" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                            Estado
                        </label>
                        <select
                            name="estado"
                            id="estado"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 text-sm cursor-pointer"
                        >
                            <option value="iniciado" {{ old('estado', $expediente->estado) === 'iniciado' ? 'selected' : '' }}>Iniciado</option>
                            <option value="en_tramite" {{ old('estado', $expediente->estado) === 'en_tramite' ? 'selected' : '' }}>En trámite</option>
                            <option value="finalizado" {{ old('estado', $expediente->estado) === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                            <option value="archivado" {{ old('estado', $expediente->estado) === 'archivado' ? 'selected' : '' }}>Archivado</option>
                        </select>
                        @error('estado')
                            <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- FECHA INICIO --}}
                    <div>
                        <label for="fecha_inicio" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                            Fecha de inicio
                        </label>
                        <input
                            type="date"
                            name="fecha_inicio"
                            id="fecha_inicio"
                            value="{{ old('fecha_inicio', $expediente->fecha_inicio) }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 text-sm cursor-pointer"
                        >
                        @error('fecha_inicio')
                            <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- OBSERVACIONES --}}
                <div>
                    <label for="observaciones" class="block text-[10px] font-bold uppercase text-neutral-500 mb-1">
                        Observaciones
                    </label>
                    <textarea
                        name="observaciones"
                        id="observaciones"
                        rows="4"
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-green-500 outline-none text-sm"
                    >{{ old('observaciones', $expediente->observaciones) }}</textarea>
                    @error('observaciones')
                        <p class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                    @enderror
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
                        href="{{ route('clientes.show', $expediente->cliente_id) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-neutral-200 dark:bg-neutral-800 px-4 py-2 text-sm text-neutral-900 dark:text-neutral-100 hover:bg-neutral-300 transition border border-neutral-300 dark:border-neutral-700 font-bold shadow-sm cursor-pointer active:scale-[0.98]"
                    >
                        ← Volver
                    </a>
                </div>

            </form>

        </div>

    </div>

</x-layouts::app>