@extends('layouts.app')

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ __('Blog') }}</h1>
            <p class="mt-2 max-w-2xl text-sm leading-7 text-slate-600">
                {{ __('blog.index.lead') }}
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $vm)
                <x-blog-card :vm="$vm" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </section>
@endsection

