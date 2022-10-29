@component('mail::message')
# Kode Verifikasi Anda
Halo {{$email}},
Berikut adalah kode verifikasi anda, demi keamanan akun kode ini harap jangan di sebarkan.
<b>{{Session::get('uniqueCode')}}</b>
@component('mail::button', ['url' => 'https://laraveltuts.com'])
FreelancerOPS.com
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
