@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl space-y-6">
        @if($errors->has('delete'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ $errors->first('delete') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Users') }}</h1>
            <x-admin.button :href="route('admin.users.create')" variant="primary">{{ __('Add user') }}</x-admin.button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3 sm:px-6">{{ __('Name') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Email') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Role') }}</th>
                            <th class="px-4 py-3 text-right sm:px-6">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 font-medium text-slate-900 sm:px-6">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $user->email }}</td>
                                <td class="px-4 py-3 sm:px-6">
                                    @if($user->is_admin)
                                        <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-800">{{ __('Administrator') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">{{ __('Editor') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-admin.action-icon
                                            :href="route('admin.users.edit', $user)"
                                            icon="pencil"
                                            :title="__('Edit')"
                                        />
                                        @if($user->id !== auth()->id())
                                            <x-admin.confirm-delete
                                                :delete-url="route('admin.users.destroy', $user)"
                                                :message="__('confirm.delete_user')"
                                                :item-name="$user->email"
                                            >
                                                <x-admin.action-icon icon="trash" variant="danger" :title="__('Delete')" />
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
