<a {{ $attributes->merge([
    'class' => 'flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-lg transition'
]) }}>
    {{ $slot }}
</a>
