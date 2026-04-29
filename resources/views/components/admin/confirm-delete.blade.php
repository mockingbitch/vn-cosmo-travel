@props([
    'deleteUrl' => null,
    'title' => null,
    'message' => null,
    'description' => null,
    'itemName' => null,
    'confirmLabel' => null,
    'cancelLabel' => null,
])

@php
    $confirmTitle = $title ?: __('Confirm delete');
    $confirmMessage = $message ?: __('Are you sure you want to delete this item?');
    $confirmDescription = $description ?: __('This action cannot be undone.');
    $confirmLabelText = $confirmLabel ?: __('Delete');
    $cancelLabelText = $cancelLabel ?: __('Cancel');
    $deleteUrlJs = $deleteUrl !== null ? \Illuminate\Support\Js::from((string) $deleteUrl) : 'null';
    $itemNameJs = $itemName !== null ? \Illuminate\Support\Js::from((string) $itemName) : 'null';
@endphp

<div x-data="{
    open: false,
    submitting: false,
    deleteUrl: {{ $deleteUrlJs }},
    itemName: {{ $itemNameJs }},
}">
    <span class="contents" @click="open = true">
        {{ $slot }}
    </span>

    <x-admin.modal name="open" size="sm" :show-close="false">
        <div class="grid gap-5">
            <div class="flex items-start gap-4">
                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-rose-50 ring-4 ring-rose-50/60">
                    <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-7.036A11.96 11.96 0 0 1 12 21.75c-2.676 0-5.216-.583-7.499-1.632a.75.75 0 0 1-.387-.91A11.95 11.95 0 0 0 4.5 12.75 8.25 8.25 0 0 1 12 4.71Zm.75 4.04v3.75M12 16.5h.008v.008H12V16.5Z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-base font-semibold text-slate-900">{{ $confirmTitle }}</div>
                    <p class="mt-1 text-sm text-slate-600">{{ $confirmMessage }}</p>
                    @if($itemName !== null)
                        <p class="mt-3 truncate rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-800" x-text="itemName"></p>
                    @endif
                    <p class="mt-2 text-xs font-medium text-slate-500">{{ $confirmDescription }}</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <x-admin.button type="button" variant="secondary" @click="open = false" x-bind:disabled="submitting">
                    {{ $cancelLabelText }}
                </x-admin.button>

                <form method="POST" :action="deleteUrl" @submit="submitting = true">
                    @csrf
                    @method('DELETE')
                    <x-admin.button type="submit" variant="danger" x-bind:disabled="submitting">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21q.342.052.682.107m-.682-.107L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562q.34-.055.682-.107m0 0a48.111 48.111 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span x-show="!submitting">{{ $confirmLabelText }}</span>
                        <span x-show="submitting" x-cloak>{{ __('Deleting…') }}</span>
                    </x-admin.button>
                </form>
            </div>
        </div>
    </x-admin.modal>
</div>
