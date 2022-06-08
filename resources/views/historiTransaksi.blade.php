@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
    <div class="card mt-3 mb-3" >
        <h5 class="card-header">Histori Transaksi</h5>

        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead style="font-weight: bold">
                    <td>
                        Tanggal Transaksi
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
                            {{$payment['payment_time']}}
                        </td>
                        <td>
                            @foreach ($dataModul as $modul)
                                @if ($modul['modul_id']==$payment['modul_id'])
                                    {{$modul['title']}}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($dataFreelancer as $freelancer)
                                @if ($freelancer['cust_id']==$payment['cust_id'])
                                    {{$freelancer['nama']}}
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
