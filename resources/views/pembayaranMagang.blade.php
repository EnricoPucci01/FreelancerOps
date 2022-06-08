@extends('header')
@section('content')

<center>
    <form action={{url("/generateva/$dataModul[modul_id]")}}>
        @csrf
        @method('POST')

        <div class="card mt-3" style="width: 50%;">
            <div class="card-body">
              <h5 class="card-title">Rentang Bayaran : @money($dataModul['bayaran_min'],'IDR',true) - @money($dataModul['bayaran_max'],'IDR',true)</h5>
              <h6 class="card-subtitle mb-2 text-muted">Virtual Account</h6>
              <h6 class="card-text">Pembayaran Untuk {{$dataModul['title']}}</h6>
              <p class="card-text">{{$dataModul['deskripsi_modul']}}</p>

              <p class="card-text fw-bold">Masukan Jumlah Pembayaran:</p>
              <input type="number" class="form-control mb-3" name='grand_total'>

              <button type="button" class="btn btn-success" data-bs-target="#modalBayar" data-bs-toggle="modal">Bayar</button>
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
                  <div class="modal-body">
                    Anda akan melakukan pembayaran untuk <b>{{$dataModul['title']}}</b>, Dengan total pembayaran adalah <b>...</b>.

                    Tekan <b>Ya</b> untuk melakukan pembayaran.

                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ya</button>
                  </div>
                </div>
              </div>
            {{-------------------------------------------------------------------------------------------------------}}
        </div>
    </form>

</center>


@endsection
