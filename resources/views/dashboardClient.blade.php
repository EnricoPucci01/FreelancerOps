@extends('header')
@section('content')
    <center>
        <div>
            <div class='tdPersonalInfo'>
                <div class="card recentProjectContainerAdminFreelancer">
                    <div class="card-body" style="text-align: left">
                        <h5 class="card-title">Proyek Saya</h5>
                        <div class="listRecentProject">
                            <table>
                                @php
                                    $i = 0;

                                    foreach ($proyek as $itemProyek) {
                                        $i++;
                                        if ($i < 3) {
                                            echo "
                                        <a href='/loadDetailProyekClient/$itemProyek[proyek_id]/c' class='text-light'>
                                        <div class='card text-center mt-2 bg-light'>
                                            <div class='card-body'>
                                            <h5 class='card-title text-dark'><u>$itemProyek[nama_proyek]</u></h5>
                                            <h6 class='card-subtitle text-secondary'>Deadline: " .
                                                Carbon\Carbon::parse($itemProyek['deadline'])->format('d-m-Y') .
                                                "</h6>
                                            <p class='card-text fw-bold text-dark'>$itemProyek[desc_proyek]</p>
                                            </div>
                                        </div>
                                        </a>";
                                        }
                                    }

                                @endphp
                            </table>
                        </div>
                        <a href={{ url('/listprojectclient') }} class="card-link mt-3">Lihat Semua</a>
                    </div>
                </div>
            </div>
            <div class='tdPersonalInfo'>
                <div class="card personalInfoAdminFreelancer">
                    <div class="card-body " style="text-align: left">
                        <div class="border border-dark rounded" style="padding: 5px">
                            <h6 class="card-subtitle mb-2 text-dark fs-4">Selamat Datang,</h6>
                            <h5 class="card-title text-dark text-uppercase fw-bold fs-2">{{ session()->get('name') }}
                            </h5>
                        </div>
                        <div class="rounded" style="padding-top: 10px">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        {{-- <a href={{url("/login")}} class="btn btn-outline-primary mb-2 mt-2 fw-bold" style="width: 92%">Obrolan</a> --}}
                                        <a href={{ url('/loadChatroom') }} class="btn btn-outline-dark mb-2 mt-2 fw-bold"
                                            style="width: 100%">
                                            Obrolan</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href={{ url('/loadEditCalendar') }} class="btn btn-outline-dark mb-2 fw-bold"
                                            style="width: 100%">Tambah Acara</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href={{ url('/loadHistoriTransaksi') }} class="btn btn-outline-dark mb-2 fw-bold"
                                            style="width: 100%">Histori
                                            Transaksi</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href={{ url('/listKontrak/pengerjaan') }}
                                            class="btn btn-outline-dark mb-2 fw-bold" style="width: 100%">List
                                            Kontrak</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card infoUang mt-2">
                <div class="card-body">
                    <div class='border border-dark bg-mute rounded' style="padding:5px;">
                        <table style="width: 100%">
                            <tr>
                                <td style="width:25%;">
                                    <h6 class="card-subtitle mb-1 font-weight-bold text-dark fs-5">Total Proyek</h6>
                                    <h5 class="card-title text-dark text-uppercase fw-bold fs-4">{{ $proyekTerbit }}</h5>
                                </td>
                                <td style="border-right:2px solid black;">
                                </td>
                                <td style="width:25%;">
                                    <h6 class="ml-2 card-subtitle mb-1 font-weight-bold text-dark fs-5">Modul Selesai
                                    </h6>
                                    <table>
                                        <tr>
                                            <td>
                                                <h5 class="ml-2 card-title text-dark text-uppercase fw-bold fs-4">
                                                    {{ $modulSelesai }}</h5>
                                            </td>
                                            <td>
                                                <a href={{ url('/modulSelesai') }} class="btn btn-sm btn-dark ml-2 mb-2">
                                                    Lihat Semua ></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="border-right:2px solid black;">
                                </td>
                                <td style="width:25%;">
                                    <h6 class="ml-2 card-subtitle mb-1 font-weight-bold text-dark fs-5">Modul Dalam
                                        Pengerjaan</h6>
                                    <table>
                                        <tr>
                                            <td>
                                                <h5 class="ml-2 card-title text-dark text-uppercase fw-bold fs-4">
                                                    {{ $modulPengerjaan }}</h5>
                                            </td>
                                            <td>
                                                <a class="btn btn-dark btn-sm mb-2 ml-2"
                                                    href={{ url('/modulPengerjaan') }}>
                                                    Lihat Semua >
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="border-right:2px solid black;">
                                </td>
                                <td>
                                    <h6 class="ml-2 card-subtitle mb-1 font-weight-bold text-dark fs-6">Pengeluaran 30Hari
                                        Terakhir</h6>
                                    <h5 class="ml-2 card-title text-dark text-uppercase fw-bold fs-4">@money($pengeluaran, 'IDR', true)</h5>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </center>
    <script>
        (function() {
            navigator.serviceWorker.ready.then(function(sw) {
                return sw.sync.register('sync');
            });
        })();
    </script>
@endsection
