@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
        <div class ="card mt-3">
            <table>
                <tr>
                    <td>
                        <div class="ml-3">
                            <h3 class="card-subtitle text-muted mt-2">
                                <p class="fw-bold">Total Pendapatan</p>
                            </h3>
                            <h3 class="card-title">
                                <p class="fw-bold">@money($totalSaldo,'IDR',true)</p>
                            </h3>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card mt-3 mb-3" style="padding: 10px">
            <h3 class="card-title">Laporan Pendapatan</h3>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="{{$bulan=='Tahun'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/Tahun")}}>Tahun Ini</a>
                </li>
                <li class="nav-item">
                  <a class="{{$bulan=='January'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/January")}}>Januari</a>
                </li>
                <li class="nav-item">
                  <a class="{{$bulan=='February'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/February")}}>Februari</a>
                </li>
                <li class="nav-item">
                  <a class="{{$bulan=='March'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/March")}}>Maret</a>
                </li>
                <li class="nav-item">
                    <a class="{{$bulan=='April'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/April")}} tabindex="-1" aria-disabled="true">April</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='May'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/May")}}>Mei</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='June'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/June")}}>Juni</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='July'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/July")}}>Juli</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='August'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/August")}}>Agustus</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='September'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/September")}}>September</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='October'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/October")}}>Oktober</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='November'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/November")}}>November</a>
                  </li>
                  <li class="nav-item">
                    <a class="{{$bulan=='December'?"nav-link active": "nav-link"}}" href={{url("/loadLaporanProyekTidakBayar/December")}}>Desember</a>
                  </li>
              </ul>
            <div class="card-body" >
                <table class="table table-striped">
                    <thead class="fw-bold">
                        <td>
                            E-mail
                        </td>
                        <td>
                            Tanggal Tagihan
                        </td>
                        <td>
                            Status
                        </td>
                        <td>
                            Saluran
                        </td>
                        <td>
                            Jumlah
                        </td>
                        <td>
                            Service Fee
                        </td>
                        <td>
                            Grand Total
                        </td>
                    </thead>
                    <tbody>
                        @foreach ($dataPayment as $payment)
                        <tr>
                            <td>
                                {{$payment->email}}
                            </td>
                            <td>
                                {{Carbon\Carbon::parse($payment->created_at)->format('d-m-Y')}}
                            </td>
                            <td>
                                {{$payment->status}}
                            </td>
                            <td>
                                {{$payment->payment_channel}}
                            </td>
                            <td>
                                @money($payment->amount,'IDR',true)
                            </td>
                            <td>
                                @money($payment->service_fee,'IDR',true)
                            </td>
                            <td>
                                @money($payment->grand_total,'IDR',true)
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $dataPayment->links("pagination::bootstrap-4") }}
                <a href={{url("/chartProyekTidakBayar")}} class="btn btn-primary">Lihat Grafik</a>
            </div>
        </div>
</div>
@endsection
