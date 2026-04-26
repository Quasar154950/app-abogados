<x-layouts::app.sidebar :title="$title ?? null">
    <div
        x-data
        x-on:keydown.window.ctrl.k.prevent="$dispatch('open-global-search')"
        x-on:keydown.window.meta.k.prevent="$dispatch('open-global-search')"
    >
        <flux:main class="px-0 sm:px-2 md:px-4">
            <div class="w-full md:mx-auto md:max-w-5xl">
                {{ $slot }}
            </div>
        </flux:main>
    </div>
</x-layouts::app.sidebar>
