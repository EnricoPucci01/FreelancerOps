@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
    <div class="card mt-3 mb-3" >
        <h5 class="card-header">Histori Transaksi</h5>

        <div style="text-align: center;width: 100%;">
            <table style="display: inline-block; margin: 2px;">
                <tr>
                    <td>
                        <form action="/loadHistoriTransaksi/withFilter">
                            @csrf
                            @method('POST')
                            <table>
                                <tr>
                                    <td style="text-align: left; font-weight: bold;">
                                        <label for="birthdaytime">Filter Tanggal</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input class= "form-control" type="date" id="birthdaytime" name="fromDate">
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        <input class= "form-control"  type="date" id="birthdaytime" name="toDate">
                                    </td>
                                    <td>
                                        <button button type="submit" class="btn btn-warning form-control"><i class="bi bi-search"></i></button>
                                    </td>
                                    <td>
                                        <a href={{url("/loadHistoriTransaksi/noFilter")}} class="btn btn-secondary form-control"><i class="bi bi-arrow-clockwise"></i></a>
                                    </td>

                                </tr>
                            </table>
                        </form>
                    </td>

                    <td>
                        <div style="margin-left: 20px; padding:10px" class="border border-dark">
                            <table>
                                <tr>
                                    <td style="font-size: 25px;">
                                        Total Transaksi
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 25px; font-weight:bold">
                                        {{$totalTrans}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead style="font-weight: bold">
                    <td>
                        Tanggal Transaksi
                    </td>
                    <td>
                        Tanggal Pembayaran
                    </td>
                    <td>
                        Modul
                    </td>
                    <td>
                        Freelancer
                    </td>
                    <td>
                        Saluran Pembayaran
                    </td>
                    <td>
                        Harga Modul
                    </td>
                    <td>
                        Service Fee
                    </td>
                    <td>
                        Total
                    </td>
                </thead>
                @foreach ($dataPayment as $payment)
                    <tr>
                        <td>
                            {{Carbon\Carbon::parse($payment['created_at'])->addDays(1)->format('d-m-Y')}}
                        </td>
                        <td>
                            @if ($payment['payment_time'] == null)

                            @else
                            {{Carbon\Carbon::parse($payment['payment_time'])->format('d-m-Y H:i')}}
                            @endif
                        </td>
                        <td>
                            @foreach ($dataModul as $modul)
                                @if ($modul['modul_id']==$payment['modul_id'])
                                    <a href={{url("/loadDetailProyekClient/$modul[proyek_id]/c")}}>{{$modul['title']}}</a>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($dataFreelancer as $freelancer)
                                @if ($freelancer['cust_id']==$payment['cust_id'])
                                    <a href={{url("/loadProfilKontrak/v/$freelancer[cust_id]")}}>{{$freelancer['nama']}}</a>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            {{$payment['payment_channel']}}
                        </td>
                        <td>
                            <p class="fw-bold">@money($payment['amount'],'IDR',true)</p>
                        </td>
                        <td>
                            <p class="fw-bold">@money($payment['service_fee'],'IDR',true)</p>
                          </td>
                        <td>
                          <p class="fw-bold">@money($payment['grand_total'],'IDR',true)</p>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
</div>

@endsection
