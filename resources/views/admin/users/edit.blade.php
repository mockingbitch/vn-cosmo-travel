@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-xl">
        <x-admin.card :title="__('Edit user')" :subtitle="__('admin.users.edit_subtitle')">
            <form method="POST" action="{{ route('admin.users.update', $editUser) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input name="name" :label="__('Full name')" :value="old('name', $editUser->name)" required />
                <x-input name="email" type="email" :label="__('Email')" :value="old('email', $editUser->email)" required />

                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-semibold text-slate-600">{{ __('admin.users.password_optional_hint') }}</p>
                    <div class="mt-3 space-y-4">
                        <x-input name="password" type="password" :label="__('New password')" autocomplete="new-password" />
                        <x-input name="password_confirmation" type="password" :label="__('Confirm password')" autocomplete="new-password" />
                    </div>
                </div>

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3">
                    <input
                        type="checkbox"
                        name="is_admin"
                        value="1"
                        class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        @checked(old('is_admin', $editUser->is_admin))
                    />
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">{{ __('Administrator account') }}</span>
                        <span class="mt-0.5 block text-xs text-slate-600">{{ __('admin.users.help_is_admin') }}</span>
                    </span>
                </label>

                @error('is_admin')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror

                <div class="flex flex-wrap gap-3 pt-2">
                    <x-admin.button type="submit" variant="primary">{{ __('Save') }}</x-admin.button>
                    <x-admin.button :href="route('admin.users.index')" variant="secondary">{{ __('Cancel') }}</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
