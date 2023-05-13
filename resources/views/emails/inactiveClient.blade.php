@component('mail::message')
# Sudah lama nih tidak berkunjung
Halo {{$email}},
Anda sudah lama tidak mengunjungi FreelancerOps. Freelancer kita sudah siap nih untuk mengambil proyek anda.

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
