@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-xl">
        <x-admin.card :title="__('ui.edit_user')" :subtitle="__('admin.users.edit_subtitle')">
            <div class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                <span>{{ __('status') }}:</span>
                @if($editUser->status === \App\Models\User::STATUS_ACTIVE)
                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ __('status.active') }}</span>
                @else
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                @endif
                <span class="text-xs text-slate-500">{{ __('admin.users.status_hint_list') }}</span>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $editUser) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input name="name" :label="__('ui.full_name')" :value="old('name', $editUser->name)" required />
                <x-input name="email" type="email" :label="__('email')" :value="old('email', $editUser->email)" required />

                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-semibold text-slate-600">{{ __('admin.users.password_optional_hint') }}</p>
                    <div class="mt-3 space-y-4">
                        <x-input name="password" type="password" :label="__('ui.new_password')" autocomplete="new-password" />
                        <x-input name="password_confirmation" type="password" :label="__('ui.confirm_password')" autocomplete="new-password" />
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
                        <span class="block text-sm font-semibold text-slate-900">{{ __('ui.administrator_account') }}</span>
                        <span class="mt-0.5 block text-xs text-slate-600">{{ __('admin.users.help_is_admin') }}</span>
                    </span>
                </label>

                @error('is_admin')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror

                <div class="flex flex-wrap gap-3 pt-2">
                    <x-admin.button type="submit" variant="primary">
                        <x-icon name="save" size="sm" />
                        {{ __('save') }}
                    </x-admin.button>
                    <x-admin.button :href="route('admin.users.index')" variant="secondary">
                        <x-icon name="arrow-left" size="sm" />
                        {{ __('cancel') }}
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
