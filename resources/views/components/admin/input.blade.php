@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'help' => null,
])

@php
    use Illuminate\Support\Str;

    $fieldId = $attributes->get('id')
        ?: 'f-'.Str::slug(str_replace(['[', ']'], ['-', ''], (string) $name)).'-'.Str::random(5);
    $hintId = $fieldId.'-hint';
    $errorId = $fieldId.'-error';
    $hasError = $name !== null && $errors->has($name);
    $describedBy = collect([$help ? $hintId : null, $hasError ? $errorId : null])->filter()->implode(' ');
@endphp

<div class="grid gap-1">
    @if($label)
        <label for="{{ $fieldId }}" class="text-xs font-semibold text-slate-700">{{ $label }}</label>
    @endif

    @if($help)
        <x-admin.hint :id="$hintId" class="!mt-0">{{ $help }}</x-admin.hint>
    @endif

    <input
        id="{{ $fieldId }}"
        type="{{ $type }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value ?? old($name) }}"
        @if($hasError) aria-invalid="true" @endif
        @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->except('id')->merge(['class' => 'w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60']) }}
    />

    @if($name)
        <x-admin.error :field="$name" :id="$errorId" class="!mt-0" />
    @endif
</div>
