@extends('header')

@section('content')
    <center style="padding: 10px">
        <div class="card" style="width: 70%; margin-top: 10px;">
            <div class="card-body">
                <h3 class="card-title">Panduan Pengaturan Kalender</h3>
                <p class="card-subtitle text-secondary">Ikuti panduan di bawah ini untuk menambahkan Calendar ID dan Google service account agar anda dapat menggunakan fitur Kalender</p>
            </div>
        </div>

        <div class="card" style="width: 70%; margin-top: 20px;">
            <div class="card-body">
                <h3 class="card-title">Menambahkan Google Service Account</h3>
                <p>1. Masuk ke Google Calendar anda</p>
                <p>2. Temukan dan tekan icon dibawah ini</p>
                <img src="{{ URL::to('images/setting.png') }}" width="50" height="50">
                <p>3. Dengan menekan icon di atas akan mengeluarkan 5 pilihan, pilih <i>Settings</i></p>
                <p>4. Geser kebawah hingga menemukan <i>Share With Specific People</i></p>
                <p>5. Tekan tombol <i>+ Add People</i> seperti dibawah</p>
                <img src="{{ URL::to('images/addpeople.png') }}" width="150" height="50">
                <p>6. Setelah itu sebuah form seperti di bawah akan tampil</p>
                <img src="{{ URL::to('images/form.png') }}" width="350" height="150">
                <p>7. Pada bagian <i>Add Email or Name</i> masukan email ini <i>freelancercalendar@freelancerops.iam.gserviceaccount.com</i></p>
                <p>8. Pada bagian <i>Permision</i> pilih <i>Make Changes to Events</i></p>
                <p>9. Tekan <i>Send</i></p>
            </div>
        </div>

        <div class="card" style="width: 70%; margin-top: 20px;">
            <div class="card-body">
                <h3 class="card-title">Mendapatkan dan Menambahkan atau Mengubah Calendar ID</h3>
                <p>1. Masuk ke Google Calendar anda</p>
                <p>2. Temukan dan tekan icon dibawah ini</p>
                <img src="{{ URL::to('images/setting.png') }}" width="50" height="50">
                <p>3. Dengan menekan icon di atas akan mengeluarkan 5 pilihan, pilih <i>Settings</i></p>
                <p>4. Geser kebawah hingga menemukan <i>Integrate Calendar</i></p>
                <p>5. Pada bagian <i>Integrate Calendar</i> anda menemukan <i>Calendar ID</i></p>
                <p>6. Salin <i>Calendar ID</i> anda</p>
                <p>7. Kembali ke halaman <i>Kalender</i> pada <i>FreelancerOps.com</i></p>
                <p>8. Tekan Tombol <i>Ubah Calendar ID</i></p>
                <p>9. Setelah itu form seperti di bawah akan muncul</p>
                <img src="{{ URL::to('images/tambah.png') }}" width="500" height="150">
                <p>10. Masukan <i>Calendar ID</i> yang sudah anda salin pada form</p>
                <p>11. Tekan <i>Tambahkan</i></p>
            </div>
        </div>
    </center>
@endsection
