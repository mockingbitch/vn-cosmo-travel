@extends('layouts.app')

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $aboutDisplay['title'] }}</h1>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <article class="richtext">
            {!! $aboutDisplay['content'] !!}
        </article>
    </section>
@endsection
