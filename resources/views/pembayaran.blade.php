@extends('header')
@section('content')

<center>

    <div class="card mt-3" style="width: 50%;">
        <div class="card-body">
          <h5 class="card-title">Total : @money($dataPayment['grand_total'],'IDR',true)</h5>
          <h6 class="card-subtitle mb-2 text-muted">{{$dataPayment['payment_channel']}}</h6>
          <p class="card-text">Pembayaran Untuk {{$dataModul['title']}}</p>
          <p class="card-text">{{$dataModul['deskripsi_modul']}}</p>
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
                    <th scope="row">Biaya Modul</td>
                    <td>@money($dataPayment['amount'],'IDR',true)</td>
                    <td></td>
                    <td></td>
                  </tr>
              <tr>
                <th scope="row">Service Fee 5%</td>
                <td>@money($dataPayment['service_fee'],'IDR',true)</td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <th scope="row">Grand Total</th>
                <td><p style="font-weight: bold">@money($dataPayment['grand_total'],'IDR',true)</p></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
          <button class="btn btn-success" data-bs-target="#modalBayar" data-bs-toggle="modal">Bayar</button>
          <a href={{url("/loadDetailProyekClient/$dataProyek[proyek_id]/c/".session()->get('cust_id'))}} class="btn btn-secondary">Kembali</a>
        </div>
      </div>
      {{---------------------------------------------------MODAL-------------------------------------------------}}
      {{-- Modal Bayar --}}
      <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalBayar">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Lakukan Pembayaran?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action={{url("/simulatepayment/$dataPayment[external_id]")}}>
                @csrf
                @method('GET')
                <div class="modal-body">
                        <input type="hidden" name="grand_total" value={{$dataPayment['grand_total']}}>

                        Anda akan melakukan pembayaran untuk <b>{{$dataModul['title']}}</b>, Dengan total pembayaran adalah <b>@money($dataPayment['grand_total'],'IDR',true)</b>.

                        Tekan <b>Ya</b> untuk melakukan pembayaran.
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ya</button>
                </div>
            </form>
            </div>
          </div>
        {{-------------------------------------------------------------------------------------------------------}}
    </div>
</center>


@endsection
