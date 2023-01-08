@extends('header')
@section('content')
<center>
    <table style="height: auto">
        <tr>
            <td class='tdPersonalInfo'>
                <div class="card recentProjectContainer">
                    <div class="card-body">
                      <h5 class="card-title">Proyek Saya</h5>
                        <div class="listRecentProject">
                            <table>
                                @php
                                    $i=0;

                                    foreach ($proyek as $itemProyek) {
                                        $i++;
                                        if($i<4){
                                            echo"
                                            <a href='/loadDetailProyekClient/$itemProyek[proyek_id]/c' class='text-light'>
                                            <div class='card text-center mt-2 bg-light'>
                                                <div class='card-body'>
                                                <h5 class='card-title text-dark'><u>$itemProyek[nama_proyek]</u></h5>
                                                <h6 class='card-subtitle text-secondary'>Deadline: ".Carbon\Carbon::parse($itemProyek["deadline"])->format('d-m-Y')."</h6>
                                                <p class='card-text fw-bold text-dark'>$itemProyek[desc_proyek]</p>
                                                </div>
                                            </div>
                                            </a>";
                                        }
                                    }

                                @endphp
                            </table>
                        </div>
                      <a href={{url("/listprojectclient")}} class="card-link mt-3">Lihat Semua</a>
                    </div>
                  </div>
            </td>
            <td class='tdPersonalInfo'>
                <div class="card personalInfo ">
                    <div class="card-body ">
                        <div class="border border-dark rounded" style="height:8rem; padding:5px; margin-bottom:3px">
                            <h6 class="card-subtitle mb-2 text-dark fs-4">Selamat Datang,</h6>
                            <h5 class="card-title text-dark text-uppercase fw-bold fs-2">{{session()->get('name')}}</h5>
                        </div>
                        <div class="rounded" style="padding-top: 10px">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        {{-- <a href={{url("/login")}} class="btn btn-outline-primary mb-2 mt-2 fw-bold" style="width: 92%">Obrolan</a> --}}
                                        <a href={{url("/loadChatroom")}} class="btn btn-outline-dark mb-2 mt-2 fw-bold" style="width: 100%"> Obrolan</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href={{url("/loadEditCalendar")}} class="btn btn-outline-dark mb-2 fw-bold" style="width: 100%">Tambah Acara</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href={{url("/loadHistoriTransaksi")}} class="btn btn-outline-dark mb-2 fw-bold" style="width: 100%">Histori Transaksi</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href={{url('/listKontrak/pengerjaan')}} class="btn btn-outline-dark mb-2 fw-bold" style="width: 100%">List Kontrak</a>
                                    </td>
                                </tr>
                            </table>
                        </div>


                    </div>
                </div>
            </td>
        </tr>
    </table>
    </center>
@endsection
