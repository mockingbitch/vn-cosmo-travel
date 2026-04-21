@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
])

<label class="grid gap-1">
    @if($label)
        <span class="text-xs font-semibold text-slate-700">{{ $label }}</span>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value ?? old($name) }}"
        {{ $attributes->merge(['class' => 'w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60']) }}
    />
    @if($name)
        @error($name)
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    @endif
</label>

