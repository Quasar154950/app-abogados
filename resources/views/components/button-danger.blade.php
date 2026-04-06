<button {{ $attributes->merge([
    'class' => 'flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition'
]) }}>
    {{ $slot }}
</button>
