<div class="space-y-4">

    {{-- ACCIONES DEL CHAT --}}
    @if(auth()->user()->role === 'abogado' && !$mensajes->isEmpty())
        <div class="flex justify-end">
            <button
                type="button"
                wire:click="vaciarConversacion"
                onclick="return confirm('¿Seguro que querés borrar toda la conversación con este cliente?')"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-xs text-white font-bold hover:bg-red-700 transition"
            >
                🗑 Vaciar conversación
            </button>
        </div>
    @endif

    {{-- LISTADO MENSAJES --}}
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 p-4 h-80 overflow-y-auto">

        @if($mensajes->isEmpty())

            <p class="text-sm text-neutral-500 italic">
                Todavía no hay mensajes.
            </p>

        @else

            <div class="space-y-3">

                @foreach($mensajes as $item)

                    @php
                        $esEstudio = $item->remitente === 'estudio';
                    @endphp

                    <div class="flex {{ $esEstudio ? 'justify-end' : 'justify-start' }}">

                        <div class="max-w-[80%] rounded-2xl px-4 py-3 shadow-sm
                            {{ $esEstudio
                                ? 'bg-blue-600 text-white'
                                : 'bg-neutral-200 dark:bg-neutral-700 text-neutral-800 dark:text-white' }}">

                            <p class="text-sm leading-relaxed whitespace-pre-line">
                                {{ $item->mensaje }}
                            </p>

                            <div class="mt-2 text-[10px]
                                {{ $esEstudio
                                    ? 'text-blue-100 text-right'
                                    : 'text-neutral-500 text-left' }}">

                                <div>
                                    📤 Enviado · {{ $item->created_at->format('d/m/Y H:i') }}
                                </div>

                                @if($item->leido)

                                    <div class="mt-1">
                                        👁 Leído

                                        @if($item->leido_at)
                                            · {{ \Carbon\Carbon::parse($item->leido_at)->format('d/m/Y H:i') }}
                                        @endif
                                    </div>

                                @else

                                    <div class="mt-1">
                                        🔔 No leído
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

    {{-- FORMULARIO --}}
    <div class="flex gap-3">

        <textarea
            wire:model="mensaje"
            rows="3"
            placeholder="Escribir mensaje..."
            class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 px-4 py-3 text-sm resize-none"
        ></textarea>

        <button
            type="button"
            wire:click="enviarMensaje"
            class="shrink-0 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-bold transition"
        >
            Enviar
        </button>

    </div>

</div>