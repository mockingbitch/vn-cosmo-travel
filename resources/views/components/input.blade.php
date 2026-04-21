@props([
    'label' => null,
    'name',
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
])

<label class="block">
    @if($label)
        <span class="mb-1 block text-sm font-medium text-slate-700">{{ $label }}</span>
    @endif
    <input
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60']) }}
    />

    @error($name)
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</label>

