@component('mail::message')
# Hello, your project is finished!
Dear {{$email}},
Please pay your project first, so you can download it.
@component('mail::button', ['url' => 'https://laraveltuts.com'])
Web
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
