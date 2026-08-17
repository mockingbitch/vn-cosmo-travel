@props([
    'selected' => '',
])

<select
    {{ $attributes->merge(['class' => 'w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60']) }}
    name="duration"
>
    <option value="" @selected($selected === '')>{{ __('any') }}</option>
    @foreach (config('tour_filters.duration_options', []) as $option)
        @php
            $value = (string) ($option['value'] ?? '');
            $labelKey = (string) ($option['label_key'] ?? '');
        @endphp
        @if ($value !== '' && $labelKey !== '')
            <option value="{{ $value }}" @selected($selected === $value)>{{ __($labelKey) }}</option>
        @endif
    @endforeach
</select>
