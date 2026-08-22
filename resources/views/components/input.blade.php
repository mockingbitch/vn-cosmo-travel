@props([
    'label' => null,
    'name',
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'help' => null,
    'compact' => false,
])

@php
    use Illuminate\Support\Str;

    $inputClass = $compact
        ? 'w-full rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-900 shadow-sm placeholder:text-slate-500 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60'
        : 'w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-500 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60';

    // Explicit for/id beats an implicit wrapping label: it also lets the hint and
    // the error message be announced through aria-describedby.
    $fieldId = $attributes->get('id')
        ?: 'f-'.Str::slug(str_replace(['[', ']'], ['-', ''], (string) $name)).'-'.Str::random(5);
    $hintId = $fieldId.'-hint';
    $errorId = $fieldId.'-error';
    $hasError = $errors->has($name);
    $describedBy = collect([$help ? $hintId : null, $hasError ? $errorId : null])->filter()->implode(' ');
@endphp

<div class="block">
    @if($label)
        <label for="{{ $fieldId }}" @class([
            'mb-1 block text-slate-700',
            'text-xs font-semibold' => $compact,
            'text-sm font-medium' => ! $compact,
        ])>{{ $label }}</label>
    @endif

    @if($help)
        <p id="{{ $hintId }}" class="mb-1 flex items-start gap-1.5 text-xs leading-5 text-slate-600">
            <x-icon name="info" size="sm" class="mt-px shrink-0 text-slate-500" />
            <span>{{ $help }}</span>
        </p>
    @endif

    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        @if($hasError) aria-invalid="true" @endif
        @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->except('id')->merge(['class' => $inputClass]) }}
    />

    @error($name)
        <p id="{{ $errorId }}" role="alert" class="mt-1 flex items-start gap-1.5 text-xs font-medium text-rose-600">
            <x-icon name="exclamation-triangle" size="sm" class="mt-px shrink-0" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
