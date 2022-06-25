@extends('header')
@section('content')

<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">

    <div class ="card mt-3">
        <table>
            <tr>
                <td>
                    <div class="ml-3">
                        <h3 class="card-subtitle text-muted mt-2">
                            <p class="fw-bold">Total Saldo Masuk</p>
                        </h3>
                        <h3 class="card-title">
                            <p class="fw-bold">@money($total,'IDR',true)</p>
                        </h3>
                    </div>
                </td>
                <td>
                    <div class="ml-3">
                        <h3 class="card-subtitle text-muted mt-2">
                            <p class="fw-bold">Total Saldo Keluar</p>
                        </h3>
                        <h3 class="card-title">
                            <p class="fw-bold">@money($totalPenarikan,'IDR',true)</p>
                        </h3>
                    </div>
                </td>
            </tr>
        </table>
    </div>



    <div class="card mt-3 mb-3" >
        <h5 class="card-header">Histori Saldo Masuk</h5>

        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead class="fw-bold">
                    <td>
                        Tanggal
                    </td>
                    <td>
                        Saluran
                    </td>
                    <td>
                        Jumlah
                    </td>
                </thead>
                @foreach ($dataPayment as $payment)
                    <tr>
                        <td>
                            {{$payment['payment_time']}}
                        </td>
                        <td>
                            {{$payment['payment_channel']}}
                        </td>
                        <td>
                          <p class="fw-bold">@money($payment['service_fee'],'IDR',true)</p>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>

    <div class="card mt-3 mb-3" >
        <h5 class="card-header">Histori Saldo Keluar</h5>

        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead class="fw-bold">
                    <td>
                        Tanggal
                    </td>
                    <td>
                        No. Rekening
                    </td>
                    <td>
                        Bank
                    </td>

                    <td>
                        Jumlah
                    </td>
                </thead>
                @foreach ($dataPenarikan as $penarikan)
                    <tr>
                        <td>
                            {{$penarikan['tanggal_request']}}
                        </td>
                        <td>
                            {{$penarikan['no_rek']}}
                        </td>
                        <td>
                            {{$penarikan['bank']}}
                        </td>
                        <td>
                          <p class="fw-bold">@money($penarikan['jumlah'],'IDR',true)</p>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
</div>

@endsection
