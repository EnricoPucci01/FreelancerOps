@extends('header')
@section('content')
<center>
    <table>
        <tr>
            <td>
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
                                            echo"<div class='card text-center  mt-2'>
                                                <div class='card-body'>
                                                <h5 class='card-title'>$itemProyek[nama_proyek]</h5>
                                                <h6 class='card-subtitle text-muted'>Deadline: $itemProyek[deadline]</h6>
                                                <p class='card-text'>$itemProyek[desc_proyek]</p>
                                                </div>
                                            </div>";
                                        }
                                    }

                                @endphp
                            </table>
                        </div>
                      <a href="{{url("/listprojectclient/".session()->get('cust_id'))}}" class="card-link mt-3">Lihat Semua</a>
                    </div>
                  </div>
            </td>
            <td class='tdPersonalInfo'>
                <div class="card personalInfo">
                    <div class="card-body">
                        <div class="bg-warning" style="height:8rem; padding:5px; margin-bottom:3px">
                            <h6 class="card-subtitle mb-2 text-light fs-4">Selamat Datang,</h6>
                            <h5 class="card-title text-light text-uppercase fw-bold fs-2">{{session()->get('name')}}</h5>
                        </div>
                        <table style="width: 109%">

                            <tr>
                                <td>
                                    <a href={{url("/loadChatroom")}} class="btn btn-outline-primary mb-2 mt-2 fw-bold" style="width: 92%">Obrolan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href={{url("/loadEditCalendar")}} class="btn btn-outline-primary mb-2 fw-bold" style="width: 92%">Kalender</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href={{url("/loadHistoriTransaksi")}} class="btn btn-outline-primary mb-2 fw-bold" style="width: 92%">Histori Transaksi</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href={{url('/listKontrak/pengerjaan')}} class="btn btn-outline-primary mb-2 fw-bold" style="width: 92%">List Kontrak</a>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </td>
        </tr>
    </table>
    </center>
@endsection
