@component('mail::message')
# Kode Verifikasi Ubah Password
Halo {{$email}},
Berikut adalah kode verifikasi anda untuk mengubah password.
<br>

<b>{{Session::get('uniqueCode')}}</b>

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
