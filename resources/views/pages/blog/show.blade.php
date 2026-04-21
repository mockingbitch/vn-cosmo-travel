@extends('layouts.app')

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">{{ __('Back to blog') }}</a>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $post->title }}</h1>
            <p class="mt-3 text-sm text-slate-600">
                {{ optional($post->created_at)?->locale(app()->getLocale())->isoFormat('LL') }}
                @if($post->category)
                    <span class="mx-2 text-slate-300">•</span>
                    <span class="font-semibold text-slate-700">{{ $post->category->name }}</span>
                @endif
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <article class="richtext">
            {!! $post->content !!}
        </article>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-6">
            <h2 class="text-xl font-semibold tracking-tight text-slate-900">{{ __('Related posts') }}</h2>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">{{ __('View all arrow') }}</a>
        </div>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($relatedPosts as $vm)
                <x-blog-card :vm="$vm" />
            @endforeach
        </div>
    </section>
@endsection

