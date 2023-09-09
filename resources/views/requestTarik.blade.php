@extends('header')
@section('content')
    <center>

        <div class="card" style="width: 70%; margin-top: 20px;">
            <div class="card-body">
                <h3 class="card-title">Tarik Dana</h5>
                    <form action={{ url('/submittarikdana') }} method="POST">
                        @csrf
                        @method('POST')
                        <table>
                            <tr>
                                <td>
                                    Saldo Anda
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="fw-bold" style="font-size: 22px">@money($saldo->saldo, 'IDR', true)</p>
                                </td>
                            </tr>
                            <!-- No Rek Tujuan -->
                            <tr>
                                <td>
                                    <label for="no_rek" class="form-label">Nomor Rekening Tujuan</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group mb-3">
                                        <select class="form-select" id='norek' name="no_rek"
                                            aria-label="Default select example">
                                            @foreach ($dataRekening as $item)
                                                <option value={{ $item['norek_id'] }}>{{ $item['nomor_rek'] }} - {{$item['bank']}}</option>
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
                                    <input type="text" name="total_penarikan" id='totalPenarikan' class="form-control">
                                </td>
                            </tr>

                        </table>

                        <button type="button" class="btn btn-primary" style="margin-top: 10px" id='myBtn'
                            data-bs-target="#modalTarik" data-bs-toggle="modal">Ajukan</button>



                        {{-- -------------------------------------------------MODAL----------------------------------------------- --}}
                        {{-- Modal Tarik --}}
                        <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalTarik">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel2">Lakukan Penarikan?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Anda akan melakukan penarikan dana sejumlah
                                        <b><span id='jmlPenarikanModal'></span></b>,</b> menuju nomor rekening <b><span
                                                id='noRekPenarikanModal'></span></b>. <br>

                                        Tekan <b>Ya</b> untuk melanjutkan proses penarikan.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Ya</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- --------------------------------------------------------------------------------------------------- --}}

                        <script type="text/javascript">
                            let norek = document.getElementById('norek');
                            let penarikan = document.getElementById('totalPenarikan');
                            let btn = document.getElementById('myBtn');
                            var arrBank = @json($dataRekening);

                            norek.addEventListener('change', function() {
                                arrBank.forEach(element => {
                                    if (element['nomor_rek'] == norek.value) {
                                        bank.value = element['bank'];
                                    }
                                });
                            });

                            btn.addEventListener('click', function() {
                                var arr=[];
                                var revArr=[];
                                var newArr=[];

                                var arr=penarikan.value.split("");
                                revArr = arr.reverse();
                                console.log(revArr);
                                var count = 0;
                                for(var i = 0; i<revArr.length;i++){
                                   if(count == 3){
                                    count=0;
                                    newArr.push(".");
                                   }
                                    newArr.push(revArr[i]);
                                   count++;
                                }

                                var formatArr=[];
                                formatArr = newArr.reverse();
                                var formatedStr="";
                                for(var i = 0; i<formatArr.length;i++){
                                    formatedStr = formatedStr+formatArr[i];
                                }
                                document.getElementById('jmlPenarikanModal').innerHTML = formatedStr;
                                document.getElementById('noRekPenarikanModal').innerHTML = norek.options[norek.selectedIndex].text;
                            });
                        </script>
                        {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
                    </form>
            </div>
        </div>
    </center>
@endsection
