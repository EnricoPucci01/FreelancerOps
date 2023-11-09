@extends('header')
@section('content')
    <div style="margin: 20px 20px 20px 20px">
        <form action="{{ url("/updateProgress/$dataModul[modul_id]/$dataProyek[tipe_proyek]") }}" method="post"
            enctype="multipart/form-data">
            @method('POST')
            @csrf

            <table class="table table-striped">
                <tr>
                    <th scope="row" style="vertical-align: middle;">
                        <h1>{{ $dataModul['title'] }}</h1>
                        <h6>Status: <u data-toggle="tooltip" data-placement="bottom"
                                title="{{ $tooltip }}">{{ $statusPay }}</u></h6>
                    </th>
                    <td></td>
                    {{-- <td scope="col" style="text-align: center;  vertical-align: middle;">
                        <i class="bi bi-check2-circle" style="font-size: 3em; color:green"></i>
                    </td> --}}
                    {{-- <a href="{{url("/downloadKontrak/$kontrak[kontrak_kerja]")}}" class="btn btn-warning btn-lg"><i class="bi bi-download"></i></a> --}}
                </tr>
            </table>
            <div>
                <h6 class="text-start">{{ $dataModul['deskripsi_modul'] }}</h6>
            </div>
            @if ($dataProyek['tipe_proyek'] == 'magang')
                <h5 class="text-start">Rentang Bayaran</h5>
                <h6 class="text-start">@money($dataModul['bayaran_min'], 'IDR', true) - @money($dataModul['bayaran_max'], 'IDR', true)</h6>
            @else
                <h5 class="text-start">Total Bayaran</h5>
                <h6 class="text-start">@money($dataModul['bayaran'], 'IDR', true)</h6>
            @endif
            <hr>
            <h5 class="text-start">Progress Terakhir</h5>
            @if ($progress == null)
                Belum Ada Progress Diserahkan
            @else
                <b>{{$progress->upload_time}}</b> <br>
                {{$progress->progress}}
            @endif

            <hr>
            <h5 class="text-start">Mulai: {{Carbon\Carbon::parse($dataModul['start'])->format('d-m-Y')}}</h5>
            <h5 class="text-start">Deadline: {{Carbon\Carbon::parse($dataModul['end'])->format('d-m-Y')}}</h5>
            <hr>
            <label for="formFile" class="form-label">Masukan File Anda, Bila ada</label>
            <input class="form-control" type="file" name='fileModul' id="formFile">
            <label for="desc" class="form-label mt-3">Deskripsikan Progress Anda</label>
            <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="progDesc" maxlength="1000"
                id='desc'></textarea>

            @if ($statusPay == 'Tidak ada Pembayaran')
                <input type="checkbox" class="form-check-input ml-0" id="exampleCheck1" name='cb[]' value='finish'
                    onclick="selectionChanged()">
                <label class="form-check-label ml-3 fw-bold" for="exampleCheck1">&nbsp;Modul Sudah Selesai</label>
            @else
                <p class="text-success fw-bold">Modul Ini Telah Anda Selesaikan, Silahkan Tunggu Pembayaran Dari Client!</p>
            @endif


            <center>
                @if ($statusPay == 'Tidak ada Pembayaran')
                    <button type="submit" id='btSub' class="btn btn-success mt-3">Update Progress</button>
                @else
                    <button type="submit" id='btSub' disabled class="btn btn-success mt-3">Update Progress</button>
                @endif

                <a class="btn btn-success mt-3"
                    href={{ url("/reviewClient/$dataModul[modul_id]/$dataProyek[proyek_id]/$custId/$dataProyek[cust_id]") }}>Review
                    Client</a>
            </center>
        </form>
        <script>
            function selectionChanged(element) {
                var checkBox = document.getElementById("exampleCheck1");
                if (checkBox.checked == true) {
                    document.getElementById('btSub').innerHTML = 'Selesaikan Modul';
                }
                if (checkBox.checked == false) {
                    document.getElementById('btSub').innerHTML = 'Update Progress';
                }
            }
        </script>
    </div>
@endsection
