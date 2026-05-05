@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl space-y-6">
        @if($errors->has('delete'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ $errors->first('delete') }}
            </div>
        @endif

        @if($errors->has('status'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ $errors->first('status') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('users') }}</h1>
            <x-admin.button :href="route('admin.users.create')" variant="primary">
                <x-icon name="add" size="sm" />
                {{ __('ui.add_user') }}
            </x-admin.button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid items-end gap-3 border-b border-slate-100 bg-slate-50/70 px-4 py-4 lg:grid-cols-5">
                <label class="grid gap-1 lg:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">{{ __('ui.filter_keyword_label') }}</span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="{{ __('ui.filter_placeholder_users') }}"
                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                </label>
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('status') }}</span>
                    <select
                        name="status"
                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >
                        <option value="">{{ __('all') }}</option>
                        <option value="{{ \App\Models\User::STATUS_ACTIVE }}" @selected(($filters['status'] ?? '') === \App\Models\User::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                        <option value="{{ \App\Models\User::STATUS_DISABLED }}" @selected(($filters['status'] ?? '') === \App\Models\User::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                    </select>
                </label>
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('role') }}</span>
                    <select
                        name="role"
                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >
                        <option value="">{{ __('all') }}</option>
                        <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>{{ __('administrator') }}</option>
                        <option value="editor" @selected(($filters['role'] ?? '') === 'editor')>{{ __('editor') }}</option>
                    </select>
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white hover:bg-slate-800">
                        <x-icon name="search" size="sm" />
                        {{ __('filter') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <x-icon name="close" size="sm" />
                        {{ __('ui.clear_filter') }}
                    </a>
                </div>
            </form>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3 sm:px-6">{{ __('name') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('email') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('role') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('status') }}</th>
                            <th class="px-4 py-3 text-right sm:px-6">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 font-medium text-slate-900 sm:px-6">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $user->email }}</td>
                                <td class="px-4 py-3 sm:px-6">
                                    @if($user->is_admin)
                                        <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-800">{{ __('administrator') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">{{ __('editor') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 align-middle sm:px-6">
                                    @if($user->id === auth()->id())
                                        <label class="sr-only" for="user-status-{{ $user->id }}">{{ __('status') }}</label>
                                        <select
                                            id="user-status-{{ $user->id }}"
                                            name="status"
                                            disabled
                                            title="{{ __('admin.status_toggle.no_toggle_own_account') }}"
                                            class="w-full min-w-[9rem] cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 opacity-90"
                                        >
                                            <option value="{{ \App\Models\User::STATUS_ACTIVE }}" @selected($user->status === \App\Models\User::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                                            <option value="{{ \App\Models\User::STATUS_DISABLED }}" @selected($user->status === \App\Models\User::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                                        </select>
                                    @else
                                        <form method="post" action="{{ route('admin.users.update-status', $user) }}" class="inline-block min-w-[9rem]">
                                            @csrf
                                            @method('PATCH')
                                            @if($users->currentPage() > 1)
                                                <input type="hidden" name="page" value="{{ $users->currentPage() }}">
                                            @endif
                                            @if(!empty($filters['q']))
                                                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                            @endif
                                            @if(!empty($filters['status']))
                                                <input type="hidden" name="status" value="{{ $filters['status'] }}">
                                            @endif
                                            @if(!empty($filters['role']))
                                                <input type="hidden" name="role" value="{{ $filters['role'] }}">
                                            @endif
                                            <label class="sr-only" for="user-status-{{ $user->id }}">{{ __('status') }}</label>
                                            <select
                                                id="user-status-{{ $user->id }}"
                                                name="status"
                                                class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-medium text-slate-800 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                                onchange="this.form.requestSubmit()"
                                            >
                                                <option value="{{ \App\Models\User::STATUS_ACTIVE }}" @selected($user->status === \App\Models\User::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                                                <option value="{{ \App\Models\User::STATUS_DISABLED }}" @selected($user->status === \App\Models\User::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                                            </select>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-admin.action-icon
                                            :href="route('admin.users.edit', $user)"
                                            icon="pencil"
                                            :title="__('edit')"
                                        />
                                        @if($user->id !== auth()->id())
                                            <x-admin.confirm-delete
                                                :delete-url="route('admin.users.destroy', $user)"
                                                :message="__('confirm.delete_user')"
                                                :item-name="$user->email"
                                            >
                                                <x-admin.action-icon icon="trash" variant="danger" :title="__('delete')" />
                                            </x-admin.confirm-delete>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="border-t border-slate-100 px-4 py-4">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
@endsection
