@extends('header')
@section('content')

<style type="text/css">
    .pagination {
       justify-content: center;
    }
    </style>
<center>
@if (session()->get('role')=='freelancer')
<div class="p-3 mb-2  mt-3 alert bg-warning fw-bold" role="alert" style="width: 70%;">
    <a href={{url("/loadRecomendKategori")}} class="text-light">
        Lihat Rekomendasi Proyek Berdasarkan Kategori Proyek <i class="bi bi-arrow-right"></i>
    </a>
</div>
<div class="p-3 mb-2  mt-3 alert bg-success fw-bold" role="alert" style="width: 70%;">
    <a href="" class="text-light">
        Lihat Rekomendasi Proyek Berdasarkan Tag Proyek <i class="bi bi-arrow-right"></i>
    </a>
</div>
@endif

    <form action={{url("/cariproyek")}}>
        <div>
            <table style="width: 70%; margin-top:10px">
                <tr>

                    <td style="width: 30%">
                        <select class="selectpicker form-control"
                        data-style="btn-default btn-lg" data-live-search="true" id="kategori" name="kategori_browse[]" multiple="multiple">
                            @foreach ($listkategori as $kategori)
                              <option value="{{$kategori['kategori_id']}}">{{$kategori['nama_kategori']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="tipe_proyek">
                              <option value="normal" selected>Normal</option>
                              <option value="magang" >Magang</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name='searchProyek' value='' class="form-control">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-warning form-control"><i class="bi bi-search"></i></button>
                    </td>
                    <td>
                        <a href={{url("/browse")}} class="btn btn-secondary form-control" style="padding-top:10px"><i class="bi bi-arrow-clockwise"></i></a>
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <table>
        @foreach ($listproyek as $proyek)
            <tr>
                <div class="card mb-3" style="width: 30rem; margin-top: 20px">
                    <div class="card-body">
                        <h5 class="card-title">{{$proyek->nama_proyek}}</h5>

                        @foreach ($listkategoriJob as $katJob)
                            @if ($katJob['kategorijob_id']==$proyek->kategorijob_id)
                                <h6 class="card-subtitle mb-2 text-muted">{{$katJob['judul_kategori']}}</h5>
                            @endif
                        @endforeach


                        <p class="card-text">{{$proyek->desc_proyek}}</p>
                        <hr>
                        <div class="card-text">
                            @foreach ($listtag as $tag)
                                    @if ($proyek->proyek_id==$tag['proyek_id'])
                                        @foreach ($listkategori as $kategori)
                                            @if ($tag['kategori_id']==$kategori['kategori_id'])
                                                <p class="badge rounded-pill bg-primary" style="margin-bottom: 0px">
                                                    {{$kategori['nama_kategori']}}
                                                </p>
                                            @endif
                                        @endforeach
                                    @endif
                            @endforeach
                        </div>
                        <hr>
                        <a href="{{url("/loadProyek/$proyek->proyek_id/$custId")}}" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </tr>
        @endforeach
    </table>
    {{ $listproyek->links("pagination::bootstrap-4") }}
</center>
@endsection
