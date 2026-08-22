@props([
    /** @var \Illuminate\Support\Collection<int, \App\Models\TourPriceType> $priceTypes */
    'priceTypes',
    /** @var list<array{tour_price_type_id: string, amount: int|null, note: string}> $rows */
    'rows' => [],
    'defaultIndex' => 0,
])

@php
    use App\Support\CurrencyFormatter;

    $groupedTypes = $priceTypes->groupBy(fn ($type) => $type->categoryLabel());

    // id => label/group, so a row can show which group its type belongs to.
    $typeMeta = $priceTypes->mapWithKeys(fn ($type) => [
        (string) $type->id => [
            'name' => $type->name,
            'group' => $type->categoryLabel(),
        ],
    ])->all();

    $initialRows = [];
    foreach (array_values($rows) as $i => $row) {
        $initialRows[] = [
            '_k' => $i + 1,
            'tour_price_type_id' => (string) ($row['tour_price_type_id'] ?? ''),
            'amount' => $row['amount'] === null || $row['amount'] === '' ? null : (int) $row['amount'],
            'note' => (string) ($row['note'] ?? ''),
        ];
    }
    if ($initialRows === []) {
        $initialRows[] = ['_k' => 1, 'tour_price_type_id' => '', 'amount' => null, 'note' => ''];
    }

    $priceSymbol = CurrencyFormatter::symbol();

    $priceErrors = [];
    foreach ($errors->getMessages() as $field => $messages) {
        if ($field === 'prices' || str_starts_with($field, 'prices.')) {
            foreach ($messages as $message) {
                $priceErrors[$message] = true;
            }
        }
    }
    $priceErrors = array_keys($priceErrors);
@endphp

<div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 sm:p-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <div class="text-sm font-semibold text-slate-900">{{ __('admin.tour_form.prices_section') }}</div>
            <p class="mt-1 text-xs leading-5 text-slate-500">{{ __('admin.tour_form.prices_help') }}</p>
            <p class="mt-1 text-xs leading-5 text-slate-500">{{ __('admin.tour_form.duplicate_type_hint') }}</p>
        </div>
        <a
            href="{{ route('admin.tour-price-types.index') }}"
            target="_blank"
            rel="noopener"
            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
        >
            <x-icon name="tag" size="sm" />
            {{ __('admin.tour_form.manage_price_types') }}
        </a>
    </div>

    @if($priceErrors !== [])
        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
            <ul class="grid gap-1 text-xs text-rose-700">
                @foreach($priceErrors as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($priceTypes->isEmpty())
        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
            {{ __('admin.tour_form.no_price_types') }}
        </div>
    @else
        <div
            class="mt-4"
            x-data="{
                rows: @js($initialRows),
                typeMeta: @js($typeMeta),
                defaultIndex: {{ (int) $defaultIndex }},
                nextKey: {{ count($initialRows) + 1 }},
                addRow() {
                    this.rows.push({ _k: this.nextKey++, tour_price_type_id: '', amount: null, note: '' });
                },
                removeRow(i) {
                    this.rows.splice(i, 1);
                    if (this.rows.length === 0) {
                        this.rows.push({ _k: this.nextKey++, tour_price_type_id: '', amount: null, note: '' });
                    }
                    if (this.defaultIndex > i) {
                        this.defaultIndex--;
                    }
                    if (this.defaultIndex >= this.rows.length) {
                        this.defaultIndex = 0;
                    }
                },
                /** A price type belongs to one row only; other types of the same group stay open. */
                isTypeTaken(typeId, idx) {
                    const id = String(typeId);
                    return this.rows.some((row, i) => i !== idx && String(row.tour_price_type_id) === id);
                },
                groupOf(row) {
                    const meta = this.typeMeta[String(row.tour_price_type_id)];
                    return meta ? meta.group : '';
                },
                format(n) {
                    if (n === null || n === undefined || n === '' || Number.isNaN(n)) {
                        return '';
                    }
                    return Math.max(0, Math.floor(Number(n))).toLocaleString('en-US');
                },
                onAmountInput(row, e) {
                    const digits = e.target.value.replace(/\D/g, '');
                    if (digits === '') {
                        row.amount = null;
                        e.target.value = '';
                        return;
                    }
                    row.amount = Math.max(0, parseInt(digits, 10));
                    e.target.value = this.format(row.amount);
                },
            }"
        >
            <div class="space-y-3">
                <template x-for="(row, idx) in rows" :key="row._k">
                    <div
                        class="rounded-xl border bg-white p-4 shadow-sm transition"
                        :class="defaultIndex === idx ? 'border-slate-300 ring-1 ring-slate-900/10' : 'border-slate-200'"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ __('admin.tour_form.price_row_label') }} <span x-text="idx + 1"></span>
                                </span>
                                <span
                                    class="truncate rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600"
                                    x-show="groupOf(row) !== ''"
                                    x-text="groupOf(row)"
                                ></span>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <label
                                    class="inline-flex cursor-pointer items-center gap-2 rounded-lg border px-2.5 py-1 text-xs font-medium transition"
                                    :class="defaultIndex === idx
                                        ? 'border-slate-300 bg-slate-900/5 text-slate-900'
                                        : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                >
                                    <input
                                        type="radio"
                                        name="default_price_index"
                                        class="h-3.5 w-3.5 border-slate-300 text-slate-900 focus:ring-slate-400"
                                        :value="idx"
                                        x-model.number="defaultIndex"
                                    />
                                    {{ __('admin.tour_form.default_price') }}
                                </label>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-slate-500 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"
                                    @click="removeRow(idx)"
                                    :aria-label="'{{ __('admin.tour_form.remove_price_row') }}'"
                                    title="{{ __('admin.tour_form.remove_price_row') }}"
                                >
                                    <x-icon name="trash" size="sm" />
                                </button>
                            </div>
                        </div>

                        <div class="mt-3 grid gap-3 sm:grid-cols-3">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-600" :for="`price-type-${row._k}`">
                                    {{ __('admin.tour_form.price_row_type') }}
                                </label>
                                <select
                                    :id="`price-type-${row._k}`"
                                    class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                    :name="`prices[${idx}][tour_price_type_id]`"
                                    aria-label="{{ __('admin.tour_form.price_row_type') }}"
                                    x-model="row.tour_price_type_id"
                                >
                                    <option value="">{{ __('admin.tour_form.select_price_type') }}</option>
                                    @foreach($groupedTypes as $categoryLabel => $types)
                                        <optgroup label="{{ $categoryLabel }}">
                                            @foreach($types as $type)
                                                <option
                                                    value="{{ $type->id }}"
                                                    :disabled="isTypeTaken({{ $type->id }}, idx)"
                                                >{{ $type->name }}@unless($type->is_active) ({{ __('status.disabled') }})@endunless</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-600" :for="`price-amount-${row._k}`">
                                    {{ __('admin.tour_form.price_row_amount') }}
                                </label>
                                <div class="relative mt-1">
                                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-500">{{ $priceSymbol }}</span>
                                    <input
                                        :id="`price-amount-${row._k}`"
                                        type="text"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-7 pr-3 text-sm tabular-nums shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                        placeholder="{{ __('placeholder.tour_price') }}"
                                        :name="`prices[${idx}][amount]`"
                                        aria-label="{{ __('admin.tour_form.price_row_amount') }}"
                                        :value="format(row.amount)"
                                        @input="onAmountInput(row, $event)"
                                    />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-xs font-medium text-slate-600" :for="`price-note-${row._k}`">
                                    {{ __('admin.tour_form.price_row_note') }}
                                </label>
                                <input
                                    :id="`price-note-${row._k}`"
                                    type="text"
                                    maxlength="255"
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-2 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                    placeholder="{{ __('placeholder.price_row_note') }}"
                                    :name="`prices[${idx}][note]`"
                                    aria-label="{{ __('admin.tour_form.price_row_note') }}"
                                    x-model="row.note"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
                    @click="addRow"
                >
                    <x-icon name="plus" size="sm" />
                    {{ __('admin.tour_form.add_price_row') }}
                </button>
                <p class="text-xs leading-5 text-slate-500 sm:text-right">{{ __('admin.tour_form.default_price_help') }}</p>
            </div>
        </div>
    @endif
</div>
