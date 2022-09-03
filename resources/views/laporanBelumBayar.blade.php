@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">

    {{-- <div class="card mt-3">
        <table>
            <tr>
                <td>
                    <div class="ml-3">
                        <h3 class="card-subtitle text-muted mt-2">
                            <p class="fw-bold">Total Saldo Masuk</p>
                        </h3>
                        <h3 class="card-title">
                            <p class="fw-bold">@money($total, 'IDR', true)</p>
                        </h3>
                    </div>
                </td>
                <td>
                    <div class="ml-3">
                        <h3 class="card-subtitle text-muted mt-2">
                            <p class="fw-bold">Total Saldo Keluar</p>
                        </h3>
                        <h3 class="card-title">
                            <p class="fw-bold">@money($totalPenarikan, 'IDR', true)</p>
                        </h3>
                    </div>
                </td>
            </tr>
        </table>
    </div> --}}

    <div class="card mt-3 mb-3">
        <h5 class="card-header">Laporan Proyek Belum Terbayar</h5>


        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead class="fw-bold">
                    <td>
                        Client
                    </td>
                    <td>
                        Email
                    </td>
                    <td>
                        Nomor HP
                    </td>
                    <td>
                        Judul Modul
                    </td>
                    <td>
                        Harga Modul
                    </td>
                    <td>
                        Biaya Pelayanan
                    </td>
                    <td>
                        Total
                    </td>
                    <td>
                        Tanggal Tagihan
                    </td>
                    <td>
                        Terlambat
                    </td>
                </thead>
                @foreach ($laporanBelumBayar as $payment)
                    <tr>
                        <td>
                            {{ $payment['namaClient'] }}
                        </td>
                        <td>
                            {{ $payment['email'] }}
                        </td>
                        <td>
                            {{ $payment['hp'] }}
                        </td>
                        <td>
                            {{ $payment['judul'] }}
                        </td>
                        <td>
                            <p class="fw-bold">@money($payment['hargamodul'], 'IDR', true)</p>
                        </td>
                        <td>
                            <p class="fw-bold">@money($payment['servicefee'], 'IDR', true)</p>
                        </td>
                        <td>
                            <p class="fw-bold">@money($payment['grand'], 'IDR', true)</p>
                        </td>
                        <td>
                            {{Carbon\Carbon::parse($payment['penagihan'])->format('d-m-Y')}}
                        </td>
                        <td>
                            {{Carbon\Carbon::parse(Carbon\Carbon::now())->diffInDays(Carbon\Carbon::parse($payment['penagihan']))}} Hari
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
</div>
@endsection
