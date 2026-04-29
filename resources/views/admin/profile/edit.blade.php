@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-xl">
        <x-admin.card :title="__('Profile')" :subtitle="__('admin.profile.subtitle')">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input name="name" :label="__('Full name')" :value="auth()->user()->name" required />
                <x-input name="email" type="email" :label="__('Email')" :value="auth()->user()->email" required />
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-semibold text-slate-600">{{ __('admin.profile.change_password_hint') }}</p>
                    <div class="mt-3 space-y-4">
                        <x-input name="password" type="password" :label="__('New password')" autocomplete="new-password" />
                        <x-input name="password_confirmation" type="password" :label="__('Confirm password')" autocomplete="new-password" />
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <x-admin.button type="submit" variant="primary">{{ __('Save') }}</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
