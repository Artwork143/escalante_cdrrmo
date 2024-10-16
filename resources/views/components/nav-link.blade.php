@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-4 mb-[-3px] border-green-700 text-sm font-medium leading-5 text-green-900 focus:outline-none focus:border-green-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent mb-[-3px] text-sm font-medium leading-5 text-white hover:text-green-900 hover:border-green-700 focus:outline-none focus:text-green-900 focus:border-green-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
