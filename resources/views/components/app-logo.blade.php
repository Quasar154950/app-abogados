@props([
    'sidebar' => false,
])

@php
    $user = auth()->user();

    $nombreEstudio = $user->nombre_estudio ?? 'Mi Estudio Jurídico';

    $logo = $user->logo_estudio
        ? asset($user->logo_estudio)
        : asset('images/logo.png');
@endphp

@if($sidebar)
    <flux:sidebar.brand name="{{ $nombreEstudio }}" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            <img src="{{ $logo }}" class="h-10 w-10 object-cover rounded-full" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="{{ $nombreEstudio }}" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            <img src="{{ $logo }}" class="h-8 w-8 object-cover rounded-full" />
        </x-slot>
    </flux:brand>
@endif
