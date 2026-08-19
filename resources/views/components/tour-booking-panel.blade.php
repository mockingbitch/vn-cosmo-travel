@props([
    'tour',
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6']) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <x-tour-book-now-badge />
                <div class="text-sm font-semibold text-slate-900">{{ __('ui.book_this_tour') }}</div>
            </div>
            <div class="mt-1 text-xs text-slate-500">{{ __('ui.well_contact_you_quickly_to_confirm_details') }}</div>
        </div>
        <div class="rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white">
            {{ $tour->formattedPrice() }}
        </div>
    </div>

    @if($tour->hasPriceOptions())
        <div class="mt-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('tour.prices') }}</div>
            <ul class="mt-2 divide-y divide-slate-100 rounded-xl border border-slate-200">
                @foreach($tour->prices as $row)
                    @continue($row->priceType === null)
                    <li class="flex items-baseline justify-between gap-3 px-3 py-2">
                        <span class="min-w-0 text-xs text-slate-600">
                            {{ $row->label() }}
                            @if(filled($row->note))
                                <span class="mt-0.5 block text-[11px] text-slate-400">{{ $row->note }}</span>
                            @endif
                        </span>
                        <span class="shrink-0 text-xs font-semibold tabular-nums text-slate-900">{{ $row->formattedAmount() }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('booking_success'))
        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('booking_success') }}
        </div>
    @endif

    <form
        class="mt-5 grid gap-4"
        method="POST"
        action="{{ route('tours.book', $tour) }}"
        x-data="{ loading: false, message: null, errorMessage: null }"
        @submit.prevent="
            loading = true;
            message = null;
            errorMessage = null;
            $el.querySelectorAll('p.text-rose-600').forEach(p => p.remove());
            fetch($el.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData($el)
            }).then(async (res) => {
                if (res.status === 429) {
                    const data = await res.json().catch(() => ({}));
                    throw { rateLimited: true, message: (data && data.message) ? data.message : '{{ __('ui.too_many_requests_please_slow_down_and_try_again_later') }}' };
                }
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    throw data;
                }
                return res.json();
            }).then((data) => {
                message = data.message;
                $el.reset();
            }).catch((err) => {
                if (err && err.rateLimited) { errorMessage = err.message; return; }
                $el.submit();
            }).finally(() => loading = false);
        "
    >
        @csrf

        <x-input label="{{ __('ui.full_name') }}" name="name" :placeholder="__('placeholder.name')" />
        <x-input label="{{ __('email') }}" name="email" type="email" :placeholder="__('placeholder.email')" />
        <x-input label="{{ __('phone') }}" name="phone" :placeholder="__('placeholder.phone')" />
        <x-input label="{{ __('ui.travel_date') }}" name="travel_date" type="date" />

        <label class="block">
            <span class="mb-1 block text-sm font-medium text-slate-700">{{ __('people') }}</span>
            <select
                name="people_count"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            >
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" @selected((int) old('people_count', 2) === $i)>{{ $i }}</option>
                @endfor
                <option value="11" @selected((int) old('people_count', 2) === 11)>{{ __('booking.people_10_plus') }}</option>
            </select>
            @error('people_count')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>

        <label class="block">
            <span class="mb-1 block text-sm font-medium text-slate-700">{{ __('ui.note_optional') }}</span>
            <textarea
                name="note"
                rows="3"
                placeholder="{{ __('placeholder.note') }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            >{{ old('note') }}</textarea>
            @error('note')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </label>

        <x-button type="submit" variant="primary" class="w-full justify-center animate-book-now-blink" x-bind:class="loading ? 'opacity-70 cursor-not-allowed' : ''">
            <x-icon name="envelope" size="sm" />
            <span x-show="!loading">{{ __('ui.book_now') }}</span>
            <span x-show="loading">{{ __('sending…') }}</span>
        </x-button>

        <template x-if="message">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" x-text="message"></div>
        </template>

        <template x-if="errorMessage">
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" x-text="errorMessage"></div>
        </template>

        <p class="text-xs leading-5 text-slate-500">
            {{ __('ui.booking_consent') }}
        </p>
    </form>
</div>
