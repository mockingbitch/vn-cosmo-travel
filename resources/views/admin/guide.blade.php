@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <p class="mb-6 text-sm text-slate-600">{{ __('admin.guide.subtitle') }}</p>

        <article
            class="guide-markdown richtext overflow-x-auto rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8"
        >
            {!! $guideHtml !!}
        </article>
    </div>
@endsection
