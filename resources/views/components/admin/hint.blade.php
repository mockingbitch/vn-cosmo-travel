@props([
    // DOM id so the field can point at this text with aria-describedby.
    'id' => null,
    // 'info' (default) for guidance, 'tip' for optional extras.
    'variant' => 'info',
])

@php
    $icon = $variant === 'tip' ? 'sparkles' : 'info';
@endphp

<p @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => 'mt-1 flex items-start gap-1.5 text-xs leading-5 text-slate-600']) }}>
    <x-icon :name="$icon" size="sm" class="mt-px shrink-0 text-slate-500" />
    <span>{{ $slot }}</span>
</p>
