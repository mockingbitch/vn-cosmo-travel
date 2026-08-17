@props([
    'label' => null,
    'name',
    'value' => null,
    'currency' => 'USD',
    'placeholder' => null,
    'compact' => false,
])

@php
    use App\Models\Tour;
    use App\Support\CurrencyFormatter;

    $currency = strtoupper((string) $currency);
    if (! array_key_exists($currency, Tour::CURRENCIES)) {
        $currency = Tour::CURRENCY_USD;
    }

    $symbol = CurrencyFormatter::symbol($currency);
    $symbolBefore = CurrencyFormatter::symbolBefore($currency);
    $resolvedValue = old($name, $value);
    $initial = filled($resolvedValue) ? (int) preg_replace('/\D/', '', (string) $resolvedValue) : null;
    $placeholderDigits = CurrencyFormatter::parse(is_string($placeholder) ? $placeholder : null);
    $placeholderFormatted = $placeholderDigits !== null
        ? CurrencyFormatter::formatAmount($placeholderDigits, $currency)
        : '';

    $inputClass = $compact
        ? 'w-full rounded-xl border border-slate-200 bg-white py-1.5 text-xs text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60 tabular-nums'
        : 'w-full rounded-xl border border-slate-200 bg-white py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60 tabular-nums';

    $paddingClass = $symbolBefore ? 'pl-7 pr-3' : 'pl-3 pr-7';
@endphp

<label
    class="block"
    x-data="currencyPriceInput(@js($initial))"
>
    @if ($label)
        <span @class([
            'mb-1 block text-slate-700',
            'text-xs font-semibold' => $compact,
            'text-sm font-medium' => ! $compact,
        ])>{{ $label }}</span>
    @endif

    <input type="hidden" name="{{ $name }}" :value="raw === null || raw === '' ? '' : raw">

    <div class="relative">
        @if ($symbolBefore)
            <span @class([
                'pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-medium text-slate-500',
                'text-xs' => $compact,
                'text-sm' => ! $compact,
            ])>{{ $symbol }}</span>
        @endif

        <input
            type="text"
            x-ref="vis"
            inputmode="numeric"
            autocomplete="off"
            placeholder="{{ $placeholderFormatted }}"
            @class([$inputClass, $paddingClass])
            @input="onInput($event)"
        />

        @if (! $symbolBefore)
            <span @class([
                'pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 font-medium text-slate-500',
                'text-xs' => $compact,
                'text-sm' => ! $compact,
            ])>{{ $symbol }}</span>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
    @enderror
</label>
