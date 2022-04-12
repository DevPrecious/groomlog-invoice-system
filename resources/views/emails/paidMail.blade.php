@component('mail::message')
# {{ $details['title'] }}

This is to inform you that your invoice has been paid.

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
