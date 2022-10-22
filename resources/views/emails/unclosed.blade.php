@component('mail::message')
# Hello, Your payment is not completed yet
Dear {{$email}},
Please complete your payment by visiting our website.
@component('mail::button', ['url' => 'https://laraveltuts.com'])
Web
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
