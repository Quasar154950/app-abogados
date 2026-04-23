@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Estudio Jurídico M. Vairo" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            <img src="/images/logo.png" class="h-10 w-10 object-contain rounded-full" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Estudio Jurídico M. Vairo" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            <img src="/images/logo.png" class="h-8 w-8 object-contain rounded-full" />
        </x-slot>
    </flux:brand>
@endif
