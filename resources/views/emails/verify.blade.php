@component('mail::message')
# Kode Verifikasi Anda
Halo {{$email}},
Berikut adalah kode verifikasi anda, demi keamanan akun kode ini harap jangan di sebarkan.
<br>

<b>{{Session::get('uniqueCode')}}</b>

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
