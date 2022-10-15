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
            <form action={{url("/loadLaporanProyekTidakBayar")}} method="GET">
                @csrf
                @method('GET')
                <table>
                    <tr>
                        <td>
                            <select class="custom-select"  name='ddPeriode' >
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Pilih Kategori
                                  </button>
                                  <div class="dropdown-menu"aria-labelledby="dropdownMenuButton">
                                    <option value="Tahun" {{$bulan=='Tahun'?"Selected": ""}} >Tahun Ini</option>
                                    <option value="January" {{$bulan=='January'?"Selected": ""}} >Januari</option>
                                    <option value="February" {{$bulan=='February'?"Selected": ""}} >Februari</option>
                                    <option value="March" {{$bulan=='March'?"Selected": ""}}>Maret</option>
                                    <option value="April" {{$bulan=='April'?"Selected": ""}}>Mei</option>
                                    <option value="June" {{$bulan=='June'?"Selected": ""}} >Juni</option>
                                    <option value="July" {{$bulan=='July'?"Selected": ""}} >Juli</option>
                                    <option value="August" {{$bulan=='August'?"Selected": ""}} >Agustus</option>
                                    <option value="September" {{$bulan=='September'?"Selected": ""}}>September</option>
                                    <option value="October" {{$bulan=='October'?"Selected": ""}} >Oktober</option>
                                    <option value="November" {{$bulan=='November'?"Selected": ""}}>November</option>
                                    <option value="December" {{$bulan=='December'?"Selected": ""}}>Desember</option>
                                  </div>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-lg">
                                <i class="bi bi-search"></i>
                            </button>
                        </td>
                    </tr>
                </table>

            </form>
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
                            Service Fee
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
                                @money($payment->service_fee,'IDR',true)
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
