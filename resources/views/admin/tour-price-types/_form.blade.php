@php
    /** @var \App\Models\TourPriceType|null $priceType */
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700" for="price-type-name">{{ __('admin.price_types.name') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.price_types.name_help') }}</p>
    <input
        id="price-type-name"
        name="name"
        value="{{ old('name', $priceType?->name) }}"
        placeholder="{{ __('placeholder.price_type_name') }}"
        maxlength="120"
        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        required
        autocomplete="off"
    >
    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700" for="price-type-category">{{ __('admin.price_types.category') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.price_types.category_help') }}</p>
    <input
        id="price-type-category"
        name="category"
        value="{{ old('category', $priceType?->category) }}"
        placeholder="{{ __('placeholder.price_type_category') }}"
        maxlength="80"
        list="price-type-categories"
        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        autocomplete="off"
    >
    <datalist id="price-type-categories">
        @foreach($categories as $category)
            <option value="{{ $category }}"></option>
        @endforeach
    </datalist>
    @error('category')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700" for="price-type-sort">{{ __('admin.price_types.sort_order') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.price_types.sort_order_help') }}</p>
    <input
        id="price-type-sort"
        name="sort_order"
        type="number"
        min="0"
        max="65535"
        value="{{ old('sort_order', $priceType?->sort_order ?? 0) }}"
        class="mt-1 w-full max-w-xs rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
    >
    @error('sort_order')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
    <input type="hidden" name="is_active" value="0">
    <input
        type="checkbox"
        name="is_active"
        value="1"
        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
        @checked((bool) old('is_active', $priceType?->is_active ?? true))
    >
    <span>
        <span class="block text-sm font-medium text-slate-800">{{ __('admin.price_types.active_label') }}</span>
        <span class="mt-0.5 block text-xs text-slate-500">{{ __('admin.price_types.active_help') }}</span>
    </span>
</label>
