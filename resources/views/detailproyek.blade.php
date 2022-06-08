@extends('header')
@section('content')

<div style="margin: 20px 20px 20px 20px">
    <form action="{{url("/ajukancv")}}" method="post" enctype="multipart/form-data">
        @method('POST')
        @csrf
        <h1>{{$dataproyek['nama_proyek']}}</h1>
        <hr>
        <div>
            <h6 class="text-start">{{$dataproyek['desc_proyek']}}</h6>
        </div>
        @if ($dataproyek['tipe_proyek']=='magang')
            <h5 class="text-start">Rentang Bayaran</h5>
            <h6 class="text-start"> @money($dataproyek['range_bayaran1'],'IDR',true) - @money($dataproyek['range_bayaran2'],'IDR',true)</h6>
        @else
            <h5 class="text-start">Total Bayaran</h5>
            <h6 class="text-start">@money($dataproyek['total_pembayaran'],'IDR',true)</h6>
        @endif


            <hr>
        <h5 class="text-start">Deadline: {{$dataproyek['deadline']}}</h5>
    <hr>
        <table class="table table-striped">
            <tr>
                <td>
                    Modul
                </td>
                <td>
                    Deskripsi
                </td>
                <td>
                    Bayaran
                </td>
                <td>
                    Deadline
                </td>
                <td>

                </td>
            </tr>
            <input type="hidden" name='id_proyek' value="{{$dataproyek['proyek_id']}}">
            @foreach ($datamodul as $modul)
                <tr>
                    <td>
                        {{$modul['title']}}
                    </td>
                    <td>
                        {{$modul['deskripsi_modul']}}
                    </td>
                    <td>
                        @if ($dataproyek['tipe_proyek']=='magang')
                            @money($modul['bayaran_min'],'IDR',true) - @money($modul['bayaran_max'],'IDR',true)
                        @else
                            @money($modul['bayaran'],'IDR',true)
                        @endif

                    </td>
                    <td>
                        {{$modul['end']}}
                    </td>
                    <td>
                        @php
                            if(in_array($modul['modul_id'],array_column($datamodultaken,'modul_id'))){
                                echo "<button type='button' class='btn btn-success bi bi-check2' data-toggle='tooltip' data-placement='top' title='Modul Telah Anda Ambil'>
                                        </button>";
                            }else if($modul['status']=='taken'||$modul['status']=='finish'){
                                echo "<button type='button' class='btn btn-danger bi bi-x' data-toggle='tooltip' data-placement='top' title='Modul Telah Di Ambil'>
                                        </button>";
                            }
                            else{
                                echo "<input class='form-check-input' type='checkbox' value=$modul[modul_id] name='checkambil[]'>";
                            }
                        @endphp

                    </td>
                </tr>
            @endforeach
        </table>

        <label for="formFile" class="form-label">Masukan Portofolio/CV anda</label>
        <input class="form-control" type="file" name='filecv' id="formFile" accept="application/pdf">
        <label for="desc" class="form-label mt-3" >Deskripsikan Mengapa Client Harus Memilih Anda?</label>
        <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="custDesc" maxlength="1000" id='desc'></textarea>

        <center>
            <button type="submit" class="btn btn-primary mt-3"> Ajukan CV </button>
            <a href={{url("/browse")}} class="btn btn-warning mt-3"> Kembali </a>
        </center>
    </form>

</div>
@endsection
