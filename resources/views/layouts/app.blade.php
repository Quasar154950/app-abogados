<x-layouts::app.sidebar :title="$title ?? null">
    {{-- Escuchador global de teclado --}}
    <div x-data x-on:keydown.window.ctrl.k.prevent="$dispatch('open-global-search')" x-on:keydown.window.meta.k.prevent="$dispatch('open-global-search')">
        
        <flux:main>
            {{ $slot }}
        </flux:main>

        {{-- Aquí insertaremos el componente del buscador en el siguiente paso --}}
        <livewire:global-search />
    </div>
</x-layouts::app.sidebar>
