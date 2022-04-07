@component('mail::message')
# {{ $details['title'] }}

Hello {{$details['email']}} this is a mail sent from Groomlog, we will like to inform you about <br>
your pending invoice <br>

please click on this link to check it out <br>

@component('mail::button', ['url' => 'http://127.0.0.1:8000/invoices/' . $details['invoice'], 'color' => 'blue'])
Click
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
