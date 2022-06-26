@extends('header')
@section('content')
<center>

        <table>
            <tr>
                <td>
                    <div class="card recentProjectContainer">
                        <div class="card-body">
                          <h5 class="card-title">Request Penarikan</h5>
                          <div class="listRecentProject">
                            <table>
                                @php
                                    $i=0;

                                    foreach ($dataPenarikan as $penarikan) {
                                        $i++;
                                        if($i<4){
                                            echo"<div class='card text-center  mt-2'>
                                            <div class='card-body'>
                                            <h5 class='card-title'>Request Penarikan Dana $penarikan[bank]</h5>
                                            <h6 class='card-subtitle text-muted'>".@money($penarikan['jumlah'],'IDR',true)."</h6>
                                            <p class='card-text'>Tanggal: $penarikan[tanggal_request]</p>
                                            </div>
                                        </div>";
                                        }
                                    }
                                @endphp
                            </table>
                            </div>
                        </div>
                      </div>
                </td>
                <td class='tdPersonalInfo'>
                    <div class="card personalInfo">
                        <div class="card-body">
                            <div class="bg-warning rounded" style="height:8rem; padding:5px; margin-bottom:3px">
                                <h6 class="card-subtitle mb-2 text-light fs-4">Selamat Datang,</h6>
                                <h5 class="card-title text-light text-uppercase fw-bold fs-2">{{session()->get('name')}}</h5>
                            </div>
                            <table style="width: 109%">
                                <tr>
                                    <td>
                                        <a href="/laporanProyekAdmin/pengerjaan"><button type="button" class="btn btn-outline-primary fw-bold" style="width: 92%">Laporan Proyek</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href={{("/loadClosePayment")}} class="btn btn-outline-primary fw-bold" style="width: 92%">Penutupan Pembayaran</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card mt-3 infoUang">
                        <div class="card-body">
                            <div  class='border border-dark rounded' style="padding:5px;">
                                <h6 class="card-subtitle mb-1 font-weight-bold text-dark fs-4">Saldo</h6>
                                <h5 class="card-title text-dark text-uppercase fw-bold fs-2">@money($total,'IDR',true)</h5>
                            </div>
                            <div class="mt-2">
                                <a href={{url("/loadPenarikanDanaAdmin")}} class="btn btn-outline-primary fw-bold" style="width: 100%">Tarik Saldo</a>
                                <a href={{url("/historiSaldoAdmin")}} class="btn btn-outline-primary fw-bold mt-1" style="width: 100%">Detail Saldo</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
</center>

@endsection
