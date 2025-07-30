@props(['href' => '#', 'color' => 'gray'])

@php
    $colors = [
        'gray' => 'bg-gray-600 hover:bg-gray-700',
        'indigo' => 'bg-indigo-600 hover:bg-indigo-700',
        'red' => 'bg-red-600 hover:bg-red-700',
        'green' => 'bg-green-600 hover:bg-green-700',
        'yellow' => 'bg-yellow-600 hover:bg-yellow-700',
    ];
    
    $colorClass = $colors[$color] ?? $colors['gray'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 $colorClass"]) }}>
    {{ $slot }}
</a>