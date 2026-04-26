<x-layouts::app.sidebar :title="$title ?? null">
    <div
        x-data
        x-on:keydown.window.ctrl.k.prevent="$dispatch('open-global-search')"
        x-on:keydown.window.meta.k.prevent="$dispatch('open-global-search')"
    >
        <flux:main class="px-4">
            <div class="w-full px-2 sm:px-3 md:mx-auto md:max-w-5xl md:px-4">
                {{ $slot }}
            </div>
        </flux:main>
    </div>
</x-layouts::app.sidebar>
