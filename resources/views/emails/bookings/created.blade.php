<x-mail::message>
# {{ $forCustomer ? __('mail.customer.title') : __('mail.admin.title') }}

@if($forCustomer)
{{ __('mail.customer.intro', ['name' => $booking->name, 'tour' => $booking->tour?->title ?? '']) }}

{{ __('mail.customer.followup') }}
@else
{{ __('mail.admin.intro', ['tour' => $booking->tour?->title ?? '']) }}
@endif

<x-mail::panel>
**{{ __('mail.field.name') }}:** {{ $booking->name }}  
**{{ __('mail.field.email') }}:** {{ $booking->email }}  
**{{ __('mail.field.phone') }}:** {{ $booking->phone }}  
**{{ __('mail.field.travel_date') }}:** {{ optional($booking->travel_date)->format('Y-m-d') }}  
**{{ __('mail.field.people') }}:** {{ $booking->people_count }}  
**{{ __('mail.field.status') }}:** {{ __('status.'.$booking->status) }}  
@if($booking->note)
**{{ __('mail.field.note') }}:** {{ $booking->note }}
@endif
</x-mail::panel>

{{ __('Thanks') }},<br>
{{ config('app.name') }}
</x-mail::message>
