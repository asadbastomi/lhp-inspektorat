@props(['href', 'active' => false])

@php
    $classes =
        $active ?? false
            ? 'block p-3 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-100 shadow-sm'
            : 'block p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-all duration-200 hover:shadow-sm';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
