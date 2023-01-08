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
              <input type="number" class="form-control mb-3" name='grand_total' id="grand_total">

              <button type="button" class="btn btn-success" data-bs-target="#modalBayar" id="myBtn" data-bs-toggle="modal">Bayar</button>
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
                    Anda akan melakukan pembayaran untuk <b>{{$dataModul['title']}}</b>, Dengan total pembayaran adalah <b><span id="grandTotalSpan"></span></b>.

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
<script type="text/javascript">
    let grand_total = document.getElementById('grand_total');
    let btn = document.getElementById('myBtn');


    // grand_total.addEventListener('keyup', function() {
    //     var arr=[];
    //     var revArr=[];
    //     var newArr=[];
    //     var cleanSTR = grand_total.value.replace(".","");
    //     cleanSTR=cleanSTR.replace(".","");
    //     cleanSTR=cleanSTR.replace(".","");
    //     cleanSTR=cleanSTR.replace(".","");
    //     cleanSTR=cleanSTR.replace(".","");
    //     cleanSTR=cleanSTR.replace(".","");
    //     var arr=cleanSTR.split("");
    //     console.log(cleanSTR);
    //     revArr = arr.reverse();
    //     console.log(revArr);
    //     var count = 0;
    //     for(var i = 0; i<revArr.length;i++){
    //        if(count == 3){
    //         count=0;
    //         newArr.push(".");
    //        }
    //         newArr.push(revArr[i]);
    //        count++;
    //     }

    //     var formatArr=[];
    //     formatArr = newArr.reverse();
    //     var formatedStr="";
    //     for(var i = 0; i<formatArr.length;i++){
    //         formatedStr = formatedStr+formatArr[i];
    //     }
    //     console.log("format: "+formatedStr)
    //     grand_total.value=formatedStr;
    // })

    btn.addEventListener('click', function() {
        var arr=[];
        var revArr=[];
        var newArr=[];
        var arr=grand_total.value.split("");
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
        document.getElementById('grandTotalSpan').innerHTML = formatedStr;
    });
</script>

@endsection
