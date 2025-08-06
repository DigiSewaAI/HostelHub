@props(['href'])

@php
    $classes = $active ?? false
                ? 'block px-4 py-2 hover:bg-blue-700 bg-blue-700 text-white'
                : 'block px-4 py-2 hover:bg-blue-700 text-gray-200';
@endphp

@if(Str::startsWith($href, 'http'))
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <a href="{{ route($href) }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@endif
