@component('mail::message')
# Apakah Anda Memiliki Masukan Atau Masalah?
Halo {{$email}},
Bila anda memiliki masalah atau masukan yang ingin di sampaikan anda bisa mengisi kuisioner dibawah.
@component('mail::button', ['url' => 'https://docs.google.com/forms/d/e/1FAIpQLSfMrZ7UTTrE_sps11zS8rACHEKNsyq_J1rIKeCIpJJKHsl_dw/viewform?usp=sf_link'])
Isi Quisioner
@endcomponent
Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
