@extends('header')
@section('content')
    <center>

        <div class="card mt-3" style="width: 50%;">
            <div class="card-body">
                <h5 class="card-title">Total : @money($dataPayment['service_fee'], 'IDR', true)</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $dataPayment['payment_channel'] }}</h6>
                <p class="card-text">Pembayaran Untuk {{ $dataProyek['nama_proyek'] }}</p>
                <p class="card-text">{{ $dataProyek['desc_proyek'] }}</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Biaya Post</td>
                            <td>@money('100000', 'IDR', true)</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-success" data-bs-target="#modalBayar" data-bs-toggle="modal">Bayar</button>
                <a href={{ url("/loadDetailProyekClient/$dataProyek[proyek_id]/c/" . session()->get('cust_id')) }}
                    class="btn btn-secondary">Kembali</a>
            </div>
        </div>
        {{-- -------------------------------------------------MODAL----------------------------------------------- --}}
        {{-- Modal Bayar --}}
        <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalBayar">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel2">Lakukan Pembayaran?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action={{ url("/simulatepaymentPostMagang/$dataPayment[external_id]") }}>
                        @csrf
                        @method('GET')
                        <div class="modal-body">
                            <input type="hidden" name="grand_total" value={{$dataPayment['service_fee']}}>

                            Anda akan melakukan pembayaran untuk <b>{{ $dataProyek['nama_proyek'] }}</b>, Dengan total pembayaran
                            adalah <b>@money($dataPayment['service_fee'], 'IDR', true)</b>.

                            Tekan <b>Ya</b> untuk melakukan pembayaran.
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Ya</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- --------------------------------------------------------------------------------------------------- --}}
        </div>
    </center>
@endsection
