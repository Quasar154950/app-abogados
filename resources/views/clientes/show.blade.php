<x-layouts::app :title="'Expediente de ' . $cliente->nombre">

    <div class="space-y-6 pb-10 px-2 sm:px-4 md:px-0 w-full max-w-full overflow-x-hidden">

        {{-- 1. CABECERA --}}
        <x-ui.panel class="text-left font-bold">
                    <h1 class="text-2xl font-bold">Ficha del Cliente</h1>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 font-normal">
                        Información del cliente, documentación y seguimiento general.
                    </p>
                </x-ui.panel>
                
                @if(session('success'))
            <x-alert-success>{{ session('success') }}</x-alert-success>
        @endif

        @if(session('nueva_password'))
            <div class="rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4">
                <p class="text-sm font-bold text-blue-700 dark:text-blue-300 mb-2">
                    🔑 Nueva contraseña generada
                </p>
                <p class="text-sm text-neutral-700 dark:text-neutral-300">
                    Guardá esta contraseña y compartila con el cliente:
                </p>
                <p class="mt-2 inline-block rounded-lg bg-white dark:bg-neutral-900 border border-blue-200 dark:border-blue-700 px-3 py-2 text-sm font-mono font-bold text-blue-700 dark:text-blue-300">
                    {{ session('nueva_password') }}
                </p>
            </div>
        @endif

        <x-alert-error />

        {{-- 2. BLOQUE: INFORMACIÓN DEL CLIENTE --}}
        <x-ui.panel class="text-left">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-[10px] font-bold uppercase text-neutral-500 mb-1">Nombre</p>
                    <p class="text-lg font-semibold">{{ $cliente->nombre }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase text-neutral-500 mb-1">Teléfono</p>
                    <p class="text-lg">{{ $cliente->telefono }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase text-neutral-500 mb-1">Email</p>
                    <p class="text-lg">{{ $cliente->email }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase text-neutral-500 mb-1">Dirección</p>
                    <p class="text-lg">{{ $cliente->direccion }}</p>
                </div>
            </div>

            {{-- ACCESO DEL CLIENTE --}}
<div class="mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
    <p class="text-[10px] font-bold uppercase text-neutral-500 mb-2">Acceso del cliente</p>

    @if($cliente->user)
        <div class="rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex-1">
                    <p class="text-sm font-bold text-green-700 dark:text-green-300 mb-1">
                        ✅ Acceso creado
                    </p>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        Este cliente ya tiene un usuario vinculado.
                    </p>

                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="rounded-lg border border-green-200 dark:border-green-800 bg-white/70 dark:bg-neutral-900/40 px-3 py-2">
                            <p class="text-[10px] font-bold uppercase text-neutral-500 mb-1">Usuario</p>
                            <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                                {{ $cliente->user->name }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-green-200 dark:border-green-800 bg-white/70 dark:bg-neutral-900/40 px-3 py-2">
                            <p class="text-[10px] font-bold uppercase text-neutral-500 mb-1">Email de acceso</p>
                            <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100 break-all">
                                {{ $cliente->user->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2 shrink-0">
                    <form method="POST" action="{{ route('clientes.resetPassword', $cliente->id) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm text-white font-bold hover:bg-yellow-600 transition cursor-pointer active:scale-[0.98] w-full">
                            🔑 Restablecer contraseña
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('clientes.quitarAcceso', $cliente->id) }}"
                          onsubmit="return confirm('¿Seguro que querés quitar el acceso de este cliente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm text-white font-bold hover:bg-red-700 transition cursor-pointer active:scale-[0.98] w-full">
                            🗑 Quitar acceso
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="mb-3">
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                Crear acceso nuevo para este cliente
            </label>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Generá un email y contraseña para que el cliente pueda ingresar a su panel.
            </p>
        </div>

        <form method="POST" action="{{ route('clientes.crearAcceso', $cliente->id) }}" class="space-y-3" autocomplete="off">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 px-1">
                <div>
                    <input type="email"
                           name="email_acceso"
                           placeholder="Email de acceso"
                           autocomplete="off"
                           value="{{ old('email_acceso') }}"
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                </div>

                <div>
                    <input type="password"
                           name="password_acceso"
                           placeholder="Contraseña"
                           autocomplete="new-password"
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                </div>

                <div>
                    <input type="password"
                           name="password_acceso_confirmation"
                           placeholder="Confirmar contraseña"
                           autocomplete="new-password"
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                </div>
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm text-white font-bold hover:bg-green-700 transition cursor-pointer active:scale-[0.98]">
                ✔ Crear acceso cliente
            </button>
        </form>
    @endif
</div>
        </x-ui.panel>

        {{-- 3. BLOQUE: DOCUMENTACIÓN DEL CLIENTE --}}
        <x-ui.panel class="text-left">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                📂 Documentación Adjunta
            </h2>
            @livewire('actions.gestion-archivos', ['model' => $cliente])
        </x-ui.panel>

        {{-- 4. BLOQUE: EXPEDIENTES --}}
        <x-ui.panel class="text-left">
            <div class="mb-4">
                <h2 class="text-xl font-bold">Expedientes</h2>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Casos y asuntos jurídicos vinculados a este cliente.
                </p>
            </div>

            {{-- FORMULARIO NUEVO EXPEDIENTE --}}
            <div class="mb-6 border border-dashed border-neutral-300 dark:border-neutral-700 rounded-xl p-4 bg-neutral-50 dark:bg-neutral-800/30">
                <h3 class="text-sm font-bold mb-3 text-neutral-700 dark:text-neutral-300">
                    ➕ Nuevo expediente
                </h3>

                <form method="POST" action="{{ route('expedientes.store', $cliente->id) }}" class="space-y-3">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                        {{-- Número de expediente --}}
                        <div>
                            <input type="text"
                                   name="numero_expediente"
                                   placeholder="N° de expediente"
                                   class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                        </div>

                        {{-- Juzgado --}}
                        <div>
                            <input type="text"
                                   name="juzgado"
                                   placeholder="Juzgado interviniente"
                                   class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                        </div>

                        {{-- Carátula --}}
                        <div>
                            <input type="text"
                                   name="caratula"
                                   placeholder="Carátula del expediente"
                                   class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700"
                                   required>
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <select name="tipo"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                                <option value="">Seleccionar tipo</option>
                                <option value="civil">Civil</option>
                                <option value="penal">Penal</option>
                                <option value="laboral">Laboral</option>
                                <option value="familia">Familia</option>
                                <option value="comercial">Comercial</option>
                                <option value="administrativo">Administrativo</option>
                            </select>
                        </div>

                        {{-- Estado --}}
                        <div>
                            <select name="estado"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                                <option value="iniciado">Iniciado</option>
                                <option value="en_tramite">En trámite</option>
                                <option value="finalizado">Finalizado</option>
                                <option value="archivado">Archivado</option>
                            </select>
                        </div>

                        {{-- Fecha --}}
                        <div>
                            <input type="date"
                                   name="fecha_inicio"
                                   class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                        </div>

                        {{-- Observaciones --}}
                        <div class="md:col-span-2">
                            <input type="text"
                                   name="observaciones"
                                   placeholder="Observaciones breves..."
                                   class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm dark:bg-neutral-900 dark:border-neutral-700">
                        </div>
                    </div>

                    {{-- Botón --}}
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm text-white font-bold hover:bg-blue-700 transition cursor-pointer active:scale-[0.98]">
                        ✔ Crear expediente
                    </button>
                </form>
            </div>

            @if($cliente->expedientes->isEmpty())
                <div class="p-6 rounded-xl border-2 border-dashed border-neutral-200 dark:border-neutral-800">
                    <p class="text-sm text-neutral-500 italic text-center">
                        Este cliente todavía no tiene expedientes cargados.
                    </p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($cliente->expedientes as $expediente)
                        <div id="expediente-{{ $expediente->id }}" class="expediente-print rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-neutral-50 dark:bg-neutral-800/40 shadow-sm">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">

                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <h3 class="text-base font-bold text-neutral-800 dark:text-neutral-100">
                                            {{ $expediente->caratula }}
                                        </h3>

                                        @php
                                            $estadoExpediente = match($expediente->estado) {
                                                'iniciado' => 'bg-blue-600 text-white',
                                                'en_tramite' => 'bg-yellow-500 text-white',
                                                'finalizado' => 'bg-green-600 text-white',
                                                'archivado' => 'bg-neutral-700 text-white',
                                                default => 'bg-blue-600 text-white',
                                            };

                                            $textoEstadoExpediente = match($expediente->estado) {
                                                'iniciado' => 'Iniciado',
                                                'en_tramite' => 'En trámite',
                                                'finalizado' => 'Finalizado',
                                                'archivado' => 'Archivado',
                                                default => ucfirst(str_replace('_', ' ', $expediente->estado)),
                                            };
                                        @endphp

                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase shadow-sm {{ $estadoExpediente }}">
                                            {{ $textoEstadoExpediente }}
                                        </span>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-4 text-[11px] text-neutral-500 dark:text-neutral-400 font-semibold uppercase">
                                        <span>
                                            📁 Número:
                                            {{ $expediente->numero_expediente ?: 'Sin cargar' }}
                                        </span>

                                        <span>
                                            ⚖️ Juzgado:
                                            {{ $expediente->juzgado ?: 'Sin cargar' }}
                                        </span>

                                        <span>
                                            🏛️ Tipo:
                                            {{ $expediente->tipo ? ucfirst($expediente->tipo) : 'Sin definir' }}
                                        </span>

                                        <span>
                                            📅 Inicio:
                                            {{ $expediente->fecha_inicio ? \Carbon\Carbon::parse($expediente->fecha_inicio)->format('d/m/Y') : 'Sin fecha' }}
                                        </span>

                                        <span>
                                            🕒 Creado:
                                            {{ $expediente->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    @if($expediente->observaciones)
                                        <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400 italic">
                                            "{{ $expediente->observaciones }}"
                                        </p>
                                    @endif

                                    {{-- TAREAS DEL EXPEDIENTE --}}
                                    <div class="mt-4 border-t border-neutral-200 dark:border-neutral-700 pt-4 no-print">
                                        <h4 class="text-sm font-bold text-neutral-700 dark:text-neutral-200 mb-3">
                                            ⚖️ Tareas vinculadas
                                        </h4>

                                        @php
                                            $tareasExpediente = $cliente->seguimientos->where('expediente_id', $expediente->id);
                                        @endphp

                                        @if($tareasExpediente->isEmpty())
                                            <div class="rounded-lg border border-dashed border-neutral-200 dark:border-neutral-700 px-4 py-3">
                                                <p class="text-xs text-neutral-500 italic">
                                                    Este expediente todavía no tiene tareas asociadas.
                                                </p>
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                @foreach($tareasExpediente as $tarea)
                                                    @php
                                                        $estadoTarea = match($tarea->estado) {
                                                            'pendiente' => 'bg-yellow-500 text-white',
                                                            'en_curso' => 'bg-blue-600 text-white',
                                                            'resuelto' => 'bg-green-600 text-white',
                                                            default => 'bg-neutral-500 text-white',
                                                        };

                                                        $textoEstadoTarea = match($tarea->estado) {
                                                            'pendiente' => 'Pendiente',
                                                            'en_curso' => 'En curso',
                                                            'resuelto' => 'Completada',
                                                            default => ucfirst(str_replace('_', ' ', $tarea->estado)),
                                                        };

                                                        $prioridadTarea = match($tarea->prioridad) {
                                                            'alta' => 'border-red-500 text-red-600',
                                                            'media' => 'border-blue-500 text-blue-600',
                                                            'baja' => 'border-neutral-400 text-neutral-500',
                                                            default => 'border-neutral-400 text-neutral-500',
                                                        };
                                                    @endphp

                                                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-3">
                                                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase shadow-sm {{ $estadoTarea }}">
                                                                        {{ $textoEstadoTarea }}
                                                                    </span>

                                                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded border {{ $prioridadTarea }}">
                                                                        {{ $tarea->prioridad }}
                                                                    </span>

                                                                    @if($tarea->fecha_recordatorio && $tarea->estado !== 'resuelto')
                                                                        @php
                                                                            $hoy = \Carbon\Carbon::today();
                                                                            $fecha = \Carbon\Carbon::parse($tarea->fecha_recordatorio);
                                                                        @endphp

                                                                        @if($fecha->isPast() && !$fecha->isToday())
                                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase text-white shadow-sm bg-red-600">
                                                                                ⚠️ Vencida
                                                                            </span>
                                                                        @elseif($fecha->isToday())
                                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase text-white shadow-sm bg-orange-500">
                                                                                📅 Hoy
                                                                            </span>
                                                                        @elseif($fecha->diffInDays($hoy) <= 3)
                                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase text-yellow-950 shadow-sm bg-yellow-400">
                                                                                ⏳ Próxima
                                                                            </span>
                                                                        @endif
                                                                    @endif

                                                                    @if($tarea->etiqueta)
                                                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded text-white shadow-sm" style="background-color: {{ $tarea->etiqueta->color }};">
                                                                            {{ $tarea->etiqueta->nombre }}
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <p class="text-sm text-neutral-700 dark:text-neutral-200 leading-relaxed">
                                                                    {{ $tarea->descripcion }}
                                                                </p>

                                                                @if($tarea->fecha_recordatorio)
                                                                    <p class="mt-2 text-[10px] text-neutral-400 font-bold uppercase">
                                                                        📅 Fecha límite: {{ \Carbon\Carbon::parse($tarea->fecha_recordatorio)->format('d/m/Y') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- ACCIONES --}}
<div class="flex items-center gap-2 shrink-0">

{{-- VER --}}
    <a href="{{ route('expedientes.show', $expediente->id) }}"
       class="p-2 rounded-lg text-neutral-400 hover:text-blue-600 hover:bg-blue-50 transition"
       title="Ver expediente">
        👁️
    </a>

    {{-- IMPRIMIR --}}
    <a href="{{ route('expedientes.imprimir', $expediente->id) }}"
       target="_blank"
       class="p-2 rounded-lg text-neutral-400 hover:text-green-600 hover:bg-green-50 transition"
       title="Imprimir expediente">
        🖨️
    </a>

    {{-- WHATSAPP --}}
    <a href="https://wa.me/549{{ preg_replace('/\D/', '', $cliente->telefono) }}?text={{ urlencode('Hola ' . $cliente->nombre . ', soy Marcela Vairo. Te comparto información sobre tu expediente: ' . $expediente->caratula) }}"
       target="_blank"
       class="p-2 rounded-lg hover:bg-green-50 transition"
       title="Enviar WhatsApp">

        <img src="/images/whatsapp.png" style="height:18px; margin-top:1px;">
    </a>

</div>

</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.panel>

        {{-- 5. BLOQUE: TAREAS GENERALES DEL CLIENTE --}}
        <x-ui.panel class="text-left">

            <div class="mb-4">
                <h2 class="text-xl font-bold">Tareas generales del cliente</h2>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Gestión de tareas generales del cliente (no asociadas a expedientes).
                </p>
            </div>
            
            {{-- Filtros Reactivos --}}
            <div class="flex gap-2 flex-wrap mb-6 border-b border-neutral-100 dark:border-neutral-800 pb-4">
    
                <button 
                    x-on:click="$dispatch('filtrar-estado', { estado: '' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-neutral-200 dark:bg-neutral-800 px-3 py-1.5 text-sm font-bold cursor-pointer active:scale-95 transition hover:bg-neutral-300 dark:hover:bg-neutral-700 text-neutral-800 dark:text-neutral-200">
                    🗂️ Todas
                </button>

                <button 
                    x-on:click="$dispatch('filtrar-estado', { estado: 'pendiente' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-3 py-1.5 text-sm text-white font-bold cursor-pointer active:scale-95 transition hover:bg-yellow-600">
                    🟡 Pendientes
                </button>

                <button 
                    x-on:click="$dispatch('filtrar-estado', { estado: 'en_curso' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm text-white font-bold cursor-pointer active:scale-95 transition hover:bg-blue-700">
                    🔵 En curso
                </button>

                <button 
                    x-on:click="$dispatch('filtrar-estado', { estado: 'resuelto' })"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 py-1.5 text-sm text-white font-bold cursor-pointer active:scale-95 transition hover:bg-green-700">
                    🟢 Completadas
                </button>
            </div>

            @livewire('seguimiento-listado', ['cliente' => $cliente], key('seguimiento-listado-cliente-' . $cliente->id))
        </x-ui.panel>

        {{-- 6. GESTIÓN INTEGRAL DE NOTAS Y TAREAS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @livewire('actions.gestion-notas', ['cliente' => $cliente])

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 md:p-6 bg-white dark:bg-neutral-900 shadow-sm text-left">
                @livewire('seguimiento-formulario', ['cliente' => $cliente], key('seguimiento-formulario-cliente-' . $cliente->id))
            </div>
        </div>

{{-- BOTONES DE ACCIÓN FINAL --}}
<div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-neutral-100 dark:border-neutral-800">

    <a href="{{ route('clientes.index') }}"
       class="inline-flex items-center justify-center whitespace-nowrap bg-neutral-200 dark:bg-neutral-800 px-4 py-2 rounded-lg text-sm font-bold hover:bg-neutral-300 transition cursor-pointer active:scale-95 shadow-sm border border-neutral-300 dark:border-neutral-700 w-full sm:w-auto">
        ← Volver al listado
    </a>

    <a href="{{ route('clientes.edit', $cliente->id) }}"
       class="inline-flex items-center justify-center whitespace-nowrap bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-yellow-600 transition shadow-sm cursor-pointer active:scale-95 w-full sm:w-auto">
        ✏️ Editar Cliente
    </a>

</div>
    </div>

</x-layouts::app>