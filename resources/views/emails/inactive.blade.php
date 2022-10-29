@component('mail::message')
# Sudah lama nih tidak berkunjung
Halo {{$email}},
Anda sudah lama tidak mengunjungi FreelancerOps. Kita memiliki banyak proyek baru nih ayo jangan sampai kehabisan.
@component('mail::button', ['url' => 'https://laraveltuts.com'])
FreelancerOPS.com
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
