@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
    <div class="card mt-3 mb-3" >
        <h5 class="card-header">Permohonan Penutupan Pembayaran</h5>

        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead class="fw-bold">
                    <tr>
                        <td>
                            Tanggal Pembayaran
                        </td>
                        <td>
                            E-mail
                        </td>
                        <td>
                            Modul
                        </td>
                        <td>
                            Saluran
                        </td>
                        <td>
                            Status
                        </td>
                        <td>
                            Jumlah
                        </td>
                        <td>

                        </td>
                    </tr>
                </thead>
                @foreach ($dataClosedPayment as $payment)
                    <tr>
                        <td>
                            {{$payment['payment_time']}}
                        </td>
                        <td>
                            {{$payment['email']}}
                        </td>
                        <td>
                            @foreach ($dataModul as $item)
                                @if ($item['modul_id']==$payment['modul_id'])
                                    {{$item['title']}}

                                    {{---------------------------------------------------MODAL-------------------------------------------------}}
                                        {{-- Modal Close --}}
                                        <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalClose">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalToggleLabel2">Teruskan Pembayaran?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Anda akan meneruskan pembayaran untuk modul {{$item['title']}}.
                                                    Dengan jumlah pembayaran <b>@money($payment['grand_total'],'IDR',true)</b>
                                                    <br>
                                                    Tekan <b>Ya</b> untuk meneruskan pembayaran.
                                                </div>
                                                <div class="modal-footer">
                                                    <a href={{url("/teruskanPembayaran/$payment[payment_id]")}} class="btn btn-primary">Ya</a>
                                                </div>
                                            </div>
                                        </div>
                                    {{-------------------------------------------------------------------------------------------------------}}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            {{$payment['payment_channel']}}
                        </td>
                        <td>
                            <p class="text-secondary fw-bold">{{$payment['status']}}</p>
                        </td>
                        <td>
                            <p class="fw-bold">@money($payment['grand_total'],'IDR',true)</p>
                        </td>
                        <td>
                            <button class="btn btn-dark" data-bs-target="#modalClose" data-bs-toggle="modal">Tutup & Teruskan</button>
                        </td>
                    </tr>
                @endforeach

            </table>
            <a class='btn btn-secondary' href={{url("/dashboard")}}>Kembali</a>
        </div>
    </div>

    </div>
</div>

@endsection
