@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block px-3 py-2 rounded-md text-base font-medium text-white bg-indigo-600'
            : 'block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
