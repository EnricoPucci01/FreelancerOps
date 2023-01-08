@extends('header')

@section('content')
    <center style="padding: 10px">
        <div class="card" style="width: 70%; margin-top: 10px;">
            <div class="card-body">
                <h3 class="card-title">Panduan Pengaturan Kalender</h3>
                <p class="card-subtitle text-secondary">Ikuti panduan di bawah ini untuk menambahkan Calendar ID dan Google
                    service account agar anda dapat menggunakan fitur Kalender</p>
            </div>
        </div>

        <div class="card" style="width: 90%; margin-top: 20px;">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="{{ $tutorType == 'GServiceAcc' ? 'nav-link active' : 'nav-link' }}"
                        href={{ url('/tutorialCalendar/GServiceAcc/1') }}>Menambahkan Google Service Account</a>
                </li>
                <li class="nav-item">
                    <a class="{{ $tutorType == 'CalID' ? 'nav-link active' : 'nav-link' }}"
                        href={{ url('/tutorialCalendar/CalID/1') }}>Mendapatkan dan Menambahkan atau Mengubah Calendar
                        ID</a>
                </li>
            </ul>
            <div class="card-body">
                @if ($tutorType == 'GServiceAcc')
                    @if ($page == '1')
                        <p>Masuk ke Google Calendar anda</p>
                    @endif
                    @if ($page == '2')
                        <p>Tekan icon <i>Setting</i></p>
                        <img src="{{ URL::to('images/GServiceAccPG2.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '3')
                        <p>Dengan menekan icon di atas akan mengeluarkan 5 pilihan, pilih <i>Settings</i></p>
                        <img src="{{ URL::to('images/GServiceAccPG3.png') }}" width="650" height="350">
                        @endif
                    @if ($page == '4')
                        <p>Pilih kalender yang ingin anda gunakan pada web</p>
                        <img src="{{ URL::to('images/GServiceAccPG4.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '5')
                        <p>Pilih kalender anda dan pilih <i>Share With Specific People or Groups</i></p>
                        <img src="{{ URL::to('images/GServiceAccPG5.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '6')
                        <p>Tekan tombol <i>+ Add People</i> seperti dibawah</p>
                        <img src="{{ URL::to('images/GServiceAccPG6.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '7')
                        <p>Setelah itu sebuah form seperti di bawah akan tampil</p>
                        <img src="{{ URL::to('images/form.png') }}" width="350" height="150">
                    @endif
                    @if ($page == '8')
                        <p>Pada bagian <i>Add Email or Name</i> masukan email ini
                            <i>freelancercalendar@freelancerops.iam.gserviceaccount.com</i>
                        </p>
                        <img src="{{ URL::to('images/GServiceAccPG8.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '9')
                        <p>Pada bagian <i>Permision</i> pilih <i>Make Changes to Events</i></p>
                        <img src="{{ URL::to('images/GServiceAccPG9.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '10')
                        <p>Tekan <i>Send</i></p>
                        <img src="{{ URL::to('images/GServiceAccPG10.png') }}" width="650" height="350">
                    @endif
                @endif
                @if ($tutorType == 'CalID')
                    @if ($page == '1')
                        <p>Masuk ke Google Calendar anda</p>
                    @endif
                    @if ($page == '2')
                        <p>Tekan icon <i>Setting</i></p>
                        <img src="{{ URL::to('images/GServiceAccPG2.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '3')
                        <p>Dengan menekan icon di atas akan mengeluarkan 5 pilihan, pilih <i>Settings</i></p>
                        <img src="{{ URL::to('images/GServiceAccPG3.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '4')
                        <p>Pilih kalender yang ingin anda gunakan pada web</p>
                        <img src="{{ URL::to('images/GServiceAccPG4.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '5')
                        <p>Pilih kalender anda dan pilih <i>Integrate Calendar</i></p>
                        <img src="{{ URL::to('images/CalIdPG5.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '6')
                        <p>Pada bagian <i>Integrate Calendar</i> anda dapat menemukan <i>Calendar ID</i> anda</p>
                        <img src="{{ URL::to('images/CalIdPG6.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '7')
                        <p>Salin <i>Calendar ID</i> anda</p>
                    @endif
                    @if ($page == '8')
                        <p>Kembali ke halaman <i>Tambah Acara</i> pada web <i>FreelancerOps</i></p>
                        <img src="{{ URL::to('images/CalIdPG8.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '9')
                        <p>Tekan Tombol <i>Ubah Calendar ID</i></p>
                        <img src="{{ URL::to('images/CalIdPG9.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '10')
                        <p>Setelah itu form seperti di bawah akan muncul</p>
                        <img src="{{ URL::to('images/tambah.png') }}" width="500" height="150">
                    @endif
                    @if ($page == '11')
                        <p>Masukan <i>Calendar ID</i> yang sudah anda salin pada form</p>
                        <img src="{{ URL::to('images/CalIdPG11.png') }}" width="650" height="350">
                    @endif
                    @if ($page == '12')
                        <p>Tekan <i>Tambahkan</i></p>
                        <img src="{{ URL::to('images/CalIdPG12.png') }}" width="650" height="350">
                    @endif
                @endif
            </div>
            <div class="card-body">
                <a href="" id="prev" class="card-link">Sebelumnya</a>
                <a href="" id="next" class="card-link">Berikutnya</a>
            </div>
        </div>
    </center>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {

            var prev = document.getElementById('prev');
            var next = document.getElementById('next');
            var page = {{ $page }};
            var TType = "{{ $tutorType }}";
            next.addEventListener('click', () => {
                console.log("next");
                if (TType == "GServiceAcc") {
                    if (page + 1 <= 10) {
                        page = page + 1;
                    }
                }
                if (TType == "CalID") {
                    if (page + 1 <= 12) {
                        page = page + 1;
                    }
                }
                next.href = "/tutorialCalendar/" + TType +"/" + page.toString();

            });
            prev.addEventListener('click', () => {
                console.log("prev");
                if (page - 1 > 0) {
                    page = page - 1;
                }
                prev.href = "/tutorialCalendar/" + TType +"/" + page.toString();

            });
        });
    </script>
@endsection
