@props([
    'max' => 8,
])

@php
    $messages = array_values(array_unique($errors->all()));
    $shown = array_slice($messages, 0, (int) $max);
    $hidden = max(0, count($messages) - count($shown));
@endphp

@if($messages !== [])
    {{-- Focused on load so keyboard and screen-reader users land on the problem. --}}
    <div
        role="alert"
        tabindex="-1"
        x-data
        x-init="$el.focus()"
        {{ $attributes->merge(['class' => 'mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400']) }}
    >
        <div class="flex items-start gap-3">
            <span class="mt-0.5 shrink-0 text-rose-600">
                <x-icon name="exclamation-triangle" size="md" />
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-rose-900">
                    {{ __('a11y.form_errors_title', ['count' => count($messages)]) }}
                </p>
                <ul class="mt-2 list-disc space-y-1 ps-5 text-xs leading-5 text-rose-800">
                    @foreach($shown as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                    @if($hidden > 0)
                        <li>{{ __('a11y.form_errors_more', ['count' => $hidden]) }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endif
