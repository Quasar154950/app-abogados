<a {{ $attributes->merge([
    'class' => 'flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition'
]) }}>
    {{ $slot }}
</a>