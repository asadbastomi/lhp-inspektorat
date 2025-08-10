@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-md border border-transparent font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';
    
    $variantClasses = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-blue-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500',
    ][$variant] ?? $variantClasses['primary'];
    
    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        'xl' => 'px-6 py-3 text-base',
    ][$size] ?? $sizeClasses['md'];
    
    $classes = "{$baseClasses} {$variantClasses} {$sizeClasses}";
    
    // Handle disabled state
    $classes .= $attributes->get('disabled') ? ' opacity-50 cursor-not-allowed' : '';
    
    // Merge all classes
    $attributes = $attributes->merge(['class' => $classes]);
@endphp

<button type="{{ $type }}" {{ $attributes }}>
    {{ $slot }}
</button>
