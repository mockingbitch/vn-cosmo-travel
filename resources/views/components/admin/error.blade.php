@props([
    'field',
    // DOM id so the field can point at this message with aria-describedby.
    'id' => null,
])

@error($field)
    <p
        @if($id) id="{{ $id }}" @endif
        role="alert"
        {{ $attributes->merge(['class' => 'mt-1 flex items-start gap-1.5 text-xs font-medium leading-5 text-rose-700']) }}
    >
        <x-icon name="exclamation-triangle" size="sm" class="mt-px shrink-0" />
        <span>{{ $message }}</span>
    </p>
@enderror
