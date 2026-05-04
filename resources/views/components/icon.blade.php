@props([
    'name',
    'size' => 'md', // sm | md | lg  → 4 | 5 | 6 (Tailwind h/w)
    'ariaHidden' => true,
])

@php
    use App\Support\IconRegistry;

    $inner = IconRegistry::svgInner((string) $name);
    $registryKey = IconRegistry::registryKey((string) $name);
    $sizeClass = match ($size) {
        'sm' => 'h-4 w-4',
        'lg' => 'h-6 w-6',
        default => 'h-5 w-5',
    };
    $filledBrandClasses = $registryKey === 'whatsapp' ? 'stroke-none fill-current' : '';
@endphp

@if ($inner !== null)
    <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        focusable="false"
        @if (filter_var($ariaHidden, FILTER_VALIDATE_BOOLEAN)) aria-hidden="true" @endif
        {{ $attributes->class([$sizeClass, $filledBrandClasses]) }}
    >
        {!! $inner !!}
    </svg>
@endif
