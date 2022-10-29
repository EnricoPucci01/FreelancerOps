@component('mail::message')
# Sudah lama nih tidak berkunjung
Halo {{$email}},
Anda sudah lama tidak mengunjungi FreelancerOps. Freelancer kita sudah siap nih untuk mengambil proyek anda.
@component('mail::button', ['url' => 'https://laraveltuts.com'])
FreelancerOPS.com
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
