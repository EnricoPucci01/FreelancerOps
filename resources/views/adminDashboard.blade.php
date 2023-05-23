@extends('header')
@section('content')
<center>
<div>
    <div class="tdPersonalInfo">
        <div class="card recentProjectContainerAdminFreelancer">
            <div class="card-body" style="text-align:left">
              <h5 class="card-title">Permohonan Penarikan Dana Freelancer</h5>
              <div class="listRecentProject">
                <table>
                    @php
                        $i=0;

                        foreach ($dataPenarikan as $penarikan) {
                            $i++;
                            if($i<3){
                                echo"<div class='card text-center bg-light mt-2'>
                                <div class='card-body'>
                                <h5 class='card-title text-dark'>Request Penarikan Dana $penarikan->nama</h5>
                                <h6 class='card-title text-dark'>Bank $penarikan->bank</h6>
                                <h6 class='card-subtitle text-muted' style='font-size:20px'>".@money($penarikan->jumlah,'IDR',true)."</h6>
                                <p class='card-text text-muted fw-bold'>Tanggal: ".Carbon\Carbon::parse($penarikan->tanggal)->format('d-m-Y')."</p>
                                </div>
                            </div>";
                            }
                        }
                    @endphp
                    <a href="{{ url('/penarikanDanaCustomer') }}">Lihat Semua</a>
                </table>
                </div>
            </div>
          </div>
    </div>
    <div class='tdPersonalInfoAdminFreelancer'>
        <div class="card personalInfoAdminFreelancer" style="text-align: left">
            <div class="card-body">
                <div class="border border-dark rounded" style="height:8rem; padding:5px;">
                    <h6 class="card-subtitle mb-2 text-dark fs-4">Selamat Datang,</h6>
                    <h5 class="card-title text-dark text-uppercase fw-bold fs-2">{{session()->get('name')}}</h5>
                </div>
                <table style="width: 100%;" class="mt-2">
                    <tr>
                        <td>
                            <a href="/laporanProyekAdmin/pengerjaan"><button type="button" class="btn btn-outline-dark fw-bold" style="width: 100%">Laporan Proyek</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href={{("/loadClosePayment")}} class="btn btn-outline-dark fw-bold" style="width: 100%">Penutupan Pembayaran</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href={{url("/loadPembatalanFreelancer")}} class="btn btn-outline-dark fw-bold" style="width: 100%">Permohonan Pembatalan Freelancer</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href={{url("/openTag")}} class="btn btn-outline-dark fw-bold" style="width: 100%">Tambah Tag</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href={{url("/openKategori")}} class="btn btn-outline-dark fw-bold" style="width: 100%">Tambah Kategori</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="card mt-3 infoUang">
        <div class="card-body" style="text-align: left">
            <div  class='border border-dark rounded' style="padding:5px;">
                <h6 class="card-subtitle mb-1 font-weight-bold text-dark fs-4">Saldo</h6>
                <h5 class="card-title text-dark text-uppercase fw-bold fs-2">@money($total,'IDR',true)</h5>
            </div>
            <div class="mt-3">
                <a href={{url("/loadPenarikanDanaAdmin")}} class="btn btn-outline-dark fw-bold" style="width: 100%">Tarik Saldo</a>
                <a href={{url("/historiSaldoAdmin")}} class="btn btn-outline-dark fw-bold mt-1" style="width: 100%">Detail Saldo</a>
            </div>
        </div>
    </div>
</div>
</center>

@endsection
