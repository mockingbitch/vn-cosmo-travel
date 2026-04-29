@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-xl">
        <x-admin.card :title="__('New user')" :subtitle="__('admin.users.create_subtitle')">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf

                <x-input name="name" :label="__('Full name')" required />
                <x-input name="email" type="email" :label="__('Email')" required />
                <x-input name="password" type="password" :label="__('Password')" autocomplete="new-password" required />
                <x-input name="password_confirmation" type="password" :label="__('Confirm password')" autocomplete="new-password" required />

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3">
                    <input type="checkbox" name="is_admin" value="1" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_admin')) />
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">{{ __('Administrator account') }}</span>
                        <span class="mt-0.5 block text-xs text-slate-600">{{ __('admin.users.help_is_admin') }}</span>
                    </span>
                </label>

                <div class="flex flex-wrap gap-3 pt-2">
                    <x-admin.button type="submit" variant="primary">{{ __('Create') }}</x-admin.button>
                    <x-admin.button :href="route('admin.users.index')" variant="secondary">{{ __('Cancel') }}</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
