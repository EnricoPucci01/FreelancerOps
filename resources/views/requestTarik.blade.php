@extends('header')
@section('content')
<center>
    <div class="card" style="width: 70%; margin-top: 20px;" >
        <div class="card-body">
          <h3 class="card-title">Tarik Dana</h5>
          <form action={{url("/submittarikdana")}} method="POST">
            @csrf
            @method('POST')
              <table>
                        <!-- No Rek Tujuan -->
                        <tr>
                            <td>
                                <label for="no_rek" class="form-label">Nomor Rekening Tujuan</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="number" name="no_rek" id='norek' class="form-control">
                            </td>
                        </tr>

                         <!-- Nama -->
                         <tr>
                            <td>
                                <label for="bank" class="form-label">Bank</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group mb-3">
                                    <select class="form-select" name='bank' id="bank" aria-label="Default select example">
                                        @foreach ($dataBank as $item)
                                            <option value={{$item['code']}}>{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                            </td>
                        </tr>

                        <!-- total penarikan -->
                        <tr>
                            <td>
                                <label for="total_penarikan" class="form-label">Total Penarikan</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="number" name="total_penarikan" id='totalPenarikan' class="form-control">
                            </td>
                        </tr>

              </table>

              <button type="button" class="btn btn-primary" style="margin-top: 10px" id='myBtn' data-bs-target="#modalTarik" data-bs-toggle="modal">Ajukan</button>



               {{---------------------------------------------------MODAL-------------------------------------------------}}
                    {{-- Modal Tarik --}}
                    <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalTarik">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel2">Lakukan Penarikan?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Anda akan melakukan penarikan dana sejumlah <b></b>, Melalui bank <b></b> nomor rekening <b></b>. <br>

                                Tekan <b>Ya</b> untuk melanjutkan proses penarikan.
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Ya</button>
                            </div>
                        </div>
                    </div>
                {{-------------------------------------------------------------------------------------------------------}}

                <script>
                    document.getElementById("myBtn").addEventListener("click", createModal);

                    function createModal(){
                        const modal = document.getElementById('modalTarik');
                        var norek=document.getElementById('norek').value;
                        var bank=document.getElementById('bank').value;
                        var jumlah=document.getElementById('totalPenarikan').value;

                        var formatter = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',

                                        // These options are needed to round to whole numbers if that's what you want.
                                        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                                        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                                        });

                        var idr=formatter.format(jumlah);

                        modal.innerHTML = `
                            <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel2">Lakukan Penarikan?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Anda akan melakukan penarikan dana sejumlah <b>`+idr+`</b>, Melalui bank <b>`+bank+`</b> dengan nomor rekening <b>`+norek+`</b>. <br>

                                        Tekan <b>Ya</b> untuk melanjutkan proses penarikan.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Ya</button>
                                    </div>
                            </div>
                            `;
                    }
                </script>
                {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
          </form>
        </div>
      </div>
</center>
<script src="<?php echo asset('postProjectJS.js')?>"></script>
@endsection
