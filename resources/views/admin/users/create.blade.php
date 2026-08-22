@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <x-admin.card :title="__('ui.new_user')" :subtitle="__('admin.users.create_subtitle')" heading="h1">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf

                <x-input name="name" :label="__('ui.full_name')" required />
                <x-input name="email" type="email" :label="__('email')" required />
                <x-input name="password" type="password" :label="__('password')" autocomplete="new-password" required />
                <x-input name="password_confirmation" type="password" :label="__('ui.confirm_password')" autocomplete="new-password" required />

                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3">
                    <input type="checkbox" name="is_admin" value="1" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_admin')) />
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">{{ __('ui.administrator_account') }}</span>
                        <span class="mt-0.5 block text-xs text-slate-600">{{ __('admin.users.help_is_admin') }}</span>
                    </span>
                </label>

                <div>
                    <label for="user-status" class="block text-sm font-medium text-slate-700">{{ __('status') }}</label>
                    <select id="user-status" name="status" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
                        <option value="{{ \App\Models\User::STATUS_ACTIVE }}" @selected(old('status', \App\Models\User::STATUS_ACTIVE) === \App\Models\User::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                        <option value="{{ \App\Models\User::STATUS_DISABLED }}" @selected(old('status', \App\Models\User::STATUS_ACTIVE) === \App\Models\User::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <x-admin.form-actions
                    submit-label="{{ __('create') }}"
                    submit-icon="add"
                    cancel-url="{{ route('admin.users.index') }}"
                <x-admin.form-actions
                    submit-label="{{ __('create') }}"
                    submit-icon="add"
                    cancel-url="{{ route('admin.users.index') }}"
                />
            </form>
        </x-admin.card>
    </div>
@endsection
