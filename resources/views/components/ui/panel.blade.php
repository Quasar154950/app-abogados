<div {{ $attributes->merge([
    'class' => 'w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm p-4 md:p-6'
]) }}>
    {{ $slot }}
</div>