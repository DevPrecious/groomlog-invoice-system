@component('mail::message')
# {{ $details['title'] }}

@if($details['can'] == 'can')
Your invoice has just been cancelled kidnly get back to us to know the way forward.
@elseif($details['can'] == 'up')
This is to inform you that your invoice has been restored.
@endif

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
