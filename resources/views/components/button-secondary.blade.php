<a {{ $attributes->merge([
    'class' => 'flex items-center gap-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 px-4 py-2 rounded-lg transition'
]) }}>
    {{ $slot }}
</a>
