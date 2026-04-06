@props(['type' => 'primary'])

@php
    $classes = match ($type) {
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
        'secondary' => 'bg-neutral-300 text-neutral-900 hover:bg-neutral-400',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-gray-500 text-white',
    };
@endphp

<button {{ $attributes->merge(['class' => "rounded-lg px-4 py-2 text-sm $classes"]) }}>
    {{ $slot }}
</button>