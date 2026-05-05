@php
    /** @var \App\Models\Post|null $post */
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('category') }}</label>
    <select name="category_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
        <option value="">{{ __('ui.none_option') }}</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id', $post?->category_id) == $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    @error('category_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

@unless(isset($post) && $post)
<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('status') }}</label>
    <select name="status" class="mt-1 w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
        <option value="{{ \App\Models\Post::STATUS_ACTIVE }}" @selected(old('status', \App\Models\Post::STATUS_ACTIVE) === \App\Models\Post::STATUS_ACTIVE)>{{ __('status.active') }}</option>
        <option value="{{ \App\Models\Post::STATUS_DISABLED }}" @selected(old('status', \App\Models\Post::STATUS_ACTIVE) === \App\Models\Post::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
    </select>
    @error('status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
@endunless

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('title') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.slug_auto') }}</p>
    <input name="title" value="{{ old('title', $post?->title) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
    @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('thumbnail') }}</label>
    <x-admin.media-picker
        name="thumbnail_media_id"
        :multiple="false"
        :value="old('thumbnail_media_id', $post?->thumbnail_media_id)"
        :label="null"
        :help="__('Choose an image from Media Library')"
    />
    @error('thumbnail_media_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('ui.content_html_allowed') }}</label>
    <p class="mt-1 text-xs text-slate-500">
        {{ __('Use wordtohtml.net to format blog content.') }}
        <a class="font-semibold text-slate-700 underline underline-offset-4 hover:text-slate-900" href="https://wordtohtml.net/" target="_blank" rel="noopener noreferrer">wordtohtml.net</a>.
        {{ __('We will remove all font-family styles when saving.') }}
    </p>
    <textarea name="content" rows="14" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 font-mono text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>{{ old('content', $post?->content) }}</textarea>
    @error('content')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
