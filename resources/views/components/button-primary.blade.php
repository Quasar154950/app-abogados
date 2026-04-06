<button {{ $attributes->merge([
    'class' => 'flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition'
]) }}>
    {{ $slot }}
</button>
