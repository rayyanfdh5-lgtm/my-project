@props([
    'route' => '#',
    'active' => '',
])

@php
    $isActive = request()->routeIs($active ?: $route);
@endphp

<a href="{{ route($route) }}"
   {{ $attributes->merge([
       'class' => ($isActive ? 'tabs-active' : 'tabs-default') . ' flex items-center justify-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium'
   ]) }}>
    {{ $slot }}
</a>
