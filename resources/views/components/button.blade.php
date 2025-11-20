@props(['type' => 'button', 'variant' => 'primary'])

@php
    $classes = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';

    if ($variant === 'primary') {
        $classes .= ' bg-gray-800 text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500';
    } elseif ($variant === 'secondary') {
        $classes .= ' bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-indigo-500';
    } elseif ($variant === 'danger') {
        $classes .= ' bg-red-600 text-white hover:bg-red-500 focus:bg-red-700 active:bg-red-900 focus:ring-red-500';
    }
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
</button>