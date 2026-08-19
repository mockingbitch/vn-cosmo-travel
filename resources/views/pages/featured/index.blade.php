@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8">
        <x-section-title
            :title="__('featured.index.title')"
            :subtitle="__('featured.index.subtitle')"
            class="max-w-2xl"
        />

        <x-site.featured-tiles :tiles="$tiles" />
    </section>
@endsection
