@php
    /** @var \App\Models\Post|null $post */
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Category') }}</label>
    <select name="category_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
        <option value="">{{ __('None option') }}</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id', $post?->category_id) == $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    @error('category_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Title') }}</label>
    <input name="title" value="{{ old('title', $post?->title) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
    @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Slug (optional)') }}</label>
    <input name="slug" value="{{ old('slug', $post?->slug) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
    @error('slug')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Thumbnail URL') }}</label>
    <input name="thumbnail" value="{{ old('thumbnail', $post?->thumbnail) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" placeholder="https://...">
    @error('thumbnail')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Content (HTML allowed)') }}</label>
    <textarea name="content" rows="14" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 font-mono text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>{{ old('content', $post?->content) }}</textarea>
    @error('content')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
