@extends('header')
@section('content')

<div style="margin: 20px 20px 20px 20px">
    <form action="{{url("/updateProgress/$dataModul[modul_id]/$dataProyek[tipe_proyek]")}}" method="post" enctype="multipart/form-data">
        @method('POST')
        @csrf

            <table class="table table-striped" >
                <tr>
                    <th scope="row">
                        <h1> {{$dataModul['title']}}</h1>
                    </th>
                    <td></td>
                    {{-- <td scope="col" style="text-align: center;  vertical-align: middle;">
                        <a href="{{url("/downloadKontrak/$kontrak[kontrak_kerja]")}}" class="btn btn-warning btn-lg"><i class="bi bi-download"></i></a>
                    </td> --}}
                </tr>
            </table>
        <div>
            <h6 class="text-start">{{$dataModul['deskripsi_modul']}}</h6>
        </div>
        @if ($dataProyek['tipe_proyek']=='magang')
            <h5 class="text-start">Rentang Bayaran</h5>
            <h6 class="text-start">@money($dataModul['bayaran_min'],'IDR',true) - @money($dataModul['bayaran_max'],'IDR',true)</h6>

        @else
            <h5 class="text-start">Total Bayaran</h5>
            <h6 class="text-start">@money($dataModul['bayaran'],'IDR',true)</h6>
        @endif

            <hr>
        <h5 class="text-start">Deadline: {{$dataModul['end']}}</h5>
    <hr>
        <label for="formFile" class="form-label">Masukan File Anda, Bila ada</label>
        <input class="form-control" type="file" name='fileModul' id="formFile">
        <label for="desc" class="form-label mt-3" >Deskripsikan Progress Anda?</label>
        <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="progDesc" maxlength="1000" id='desc'></textarea>

        <input type="checkbox" class="form-check-input ml-0" id="exampleCheck1" name='cb[]' value='finish' onclick="selectionChanged()">
        <label class="form-check-label ml-3" for="exampleCheck1"  >Selesaikan Modul</label>

        <center>
            <button type="submit" id='btSub' class="btn btn-success mt-3">Update Progress</button>
        </center>
    </form>
<script>
    function selectionChanged(element) {
        var checkBox = document.getElementById("exampleCheck1");
        if (checkBox.checked == true){
            document.getElementById('btSub').innerHTML = 'Selesaikan Modul';
        }
        if(checkBox.checked == false){
            document.getElementById('btSub').innerHTML = 'Update Progress';
        }
    }
</script>
</div>
@endsection
