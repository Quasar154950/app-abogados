<div class="p-4 bg-white dark:bg-neutral-900 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700 text-left">
    
    {{-- Mensajes de Feedback --}}
    @if (session()->has('success'))
        <div class="mb-4 p-2 text-xs font-bold text-green-700 bg-green-100 border border-green-200 rounded-lg text-center shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <h3 class="text-md font-bold mb-4 text-gray-800 dark:text-neutral-200 flex items-center gap-2">
        📂 Gestión de Documentos
    </h3>

    {{-- Zona de Carga --}}
    <div class="mb-6 p-4 border-2 border-dashed border-gray-300 dark:border-neutral-600 rounded-lg bg-gray-50 dark:bg-neutral-800 hover:bg-gray-100 dark:hover:bg-neutral-700/50 transition relative text-center">
        <input type="file" wire:model="archivo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
        
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                @if($archivo)
                    <span class="font-bold text-blue-600 dark:text-blue-400">📄 Archivo listo: {{ $archivo->getClientOriginalName() }}</span>
                @else
                    Haga clic aquí o arrastre un archivo (PDF, Word, Imagen)
                @endif
            </p>
            <p class="text-xs text-gray-400 mt-1">Máximo 10MB</p>
        </div>

        @error('archivo') <span class="text-red-500 text-xs block mt-2 font-bold text-center italic">{{ $message }}</span> @enderror
    </div>

    {{-- Botón de Confirmación (CON MANITO Y EFECTO CLIC) --}}
    @if($archivo)
        <button wire:click="guardarArchivo" 
                wire:loading.attr="disabled" 
                class="w-full mb-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition flex justify-center items-center shadow-lg cursor-pointer active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="archivo, guardarArchivo">✅ Confirmar y Guardar</span>
            <span wire:loading wire:target="archivo, guardarArchivo">⏳ Procesando archivo...</span>
        </button>
    @endif

    <hr class="my-4 border-gray-100 dark:border-neutral-800">

    {{-- Listado de Archivos --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @forelse($archivos as $doc)
            <div class="relative group border rounded-md p-3 flex flex-col items-center bg-white dark:bg-neutral-800 hover:shadow-md transition border-gray-100 dark:border-neutral-700">
                
                {{-- BOTÓN ELIMINAR --}}
                <button wire:click="eliminarArchivo({{ $doc->id }})" 
                        wire:confirm="¿Estás seguro de que deseas eliminar este archivo?"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow-sm z-20 hover:bg-red-600 transition cursor-pointer"
                        title="Eliminar">
                    ✕
                </button>

                {{-- Link invisible para la manito de abrir (CON CARTELITO) --}}
                <a href="{{ $doc->getUrl() }}" target="_blank" class="absolute inset-0 z-10 cursor-pointer" title="Ver archivo"></a>
                
                {{-- Vista previa según tipo --}}
                @if(str_contains($doc->mime_type, 'image'))
                    <img src="{{ $doc->getUrl() }}" class="w-16 h-16 object-cover rounded shadow-sm">
                @elseif(str_contains($doc->mime_type, 'pdf'))
                    <div class="text-4xl">📕</div>
                    <span class="text-[10px] font-bold text-red-600">PDF</span>
                @elseif(str_contains($doc->mime_type, 'word') || str_contains($doc->mime_type, 'officedocument.word'))
                    <div class="text-4xl">📘</div>
                    <span class="text-[10px] font-bold text-blue-600">WORD</span>
                @elseif(str_contains($doc->mime_type, 'excel') || str_contains($doc->mime_type, 'officedocument.spreadsheet'))
                    <div class="text-4xl">📗</div>
                    <span class="text-[10px] font-bold text-green-600">EXCEL</span>
                @else
                    <div class="text-4xl">📁</div>
                    <span class="text-[10px] font-bold text-gray-500">DOC</span>
                @endif

                {{-- Nombre del archivo (CON HOVER DE COLOR Y CARTELITO) --}}
                <p class="text-[10px] mt-2 text-center text-gray-500 dark:text-neutral-400 truncate w-full px-1 group-hover:text-blue-600 transition-colors font-medium" 
                   title="Ver archivo: {{ $doc->file_name }}">
                    {{ $doc->file_name }}
                </p>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-400 text-sm py-4 italic">No hay archivos adjuntos todavía.</p>
        @endforelse
    </div>
</div>
