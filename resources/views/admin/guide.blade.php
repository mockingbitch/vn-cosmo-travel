@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.guide.title') }}</h1>
        <p class="mb-6 mt-1 text-sm text-slate-600">{{ __('admin.guide.subtitle') }}</p>

        <article
            class="guide-markdown richtext overflow-x-auto rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8"
        >
            {!! $guideHtml !!}
        </article>
    </div>
@endsection
