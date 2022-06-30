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
                                <div class="input-group mb-3">
                                    <select class="form-select" id='norek' name="no_rek" aria-label="Default select example">
                                        @foreach ($dataRekening as $item)
                                            <option value={{$item["nomor_rek"]}}>{{$item['nomor_rek']}}</option>
                                        @endforeach
                                    </select>
                                  </div>
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

                <script type="text/javascript">
                    let norek= document.getElementById('norek');
                    let bank= document.getElementById('bank');
                    var arr=@json($dataRekening);

                    norek.addEventListener('change',function() {
                        arr.forEach(element => {
                            if(element['nomor_rek']==norek.value){
                                bank.value=element['bank'];
                        }
                        });
                    });
                </script>
                {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
          </form>
        </div>
      </div>
</center>
@endsection
