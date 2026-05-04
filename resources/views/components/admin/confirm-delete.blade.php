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

    <x-admin.modal name="open" size="sm" :show-close="false" :aria-label="$confirmTitle">
        <div class="grid gap-5">
            <div class="flex items-start gap-4">
                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-rose-50 ring-4 ring-rose-50/60">
                    <x-icon name="exclamation-triangle" size="md" class="text-rose-600" />
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
                    <x-icon name="close" size="sm" />
                    {{ $cancelLabelText }}
                </x-admin.button>

                <form method="POST" :action="deleteUrl" @submit="submitting = true">
                    @csrf
                    @method('DELETE')
                    <x-admin.button type="submit" variant="danger" x-bind:disabled="submitting">
                        <x-icon name="delete" size="sm" />
                        <span x-show="!submitting">{{ $confirmLabelText }}</span>
                        <span x-show="submitting" x-cloak>{{ __('Deleting…') }}</span>
                    </x-admin.button>
                </form>
            </div>
        </div>
    </x-admin.modal>
</div>
