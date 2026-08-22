@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <x-admin.card :title="__('profile')" :subtitle="__('admin.profile.subtitle')" heading="h1">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input name="name" :label="__('ui.full_name')" :value="auth()->user()->name" required />
                <x-input name="email" type="email" :label="__('email')" :value="auth()->user()->email" required />
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-semibold text-slate-600">{{ __('admin.profile.change_password_hint') }}</p>
                    <div class="mt-3 space-y-4">
                        <x-input name="password" type="password" :label="__('ui.new_password')" autocomplete="new-password" />
                        <x-input name="password_confirmation" type="password" :label="__('ui.confirm_password')" autocomplete="new-password" />
                    </div>
                </div>

                <x-admin.form-actions
                    submit-label="{{ __('save') }}"
                    submit-icon="save"
                <x-admin.form-actions
                    submit-label="{{ __('save') }}"
                    submit-icon="save"
                />
            </form>
        </x-admin.card>
    </div>
@endsection
