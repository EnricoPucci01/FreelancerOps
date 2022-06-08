@extends('header')
@section('content')

<center>
    <h1 class="mt-2">Histori Proyek</h1>
    <table class="mb-3">
        @foreach ($modulSelesai as $modul)
            <tr>
                <div class="card" style="width: 30rem; margin-top: 20px">
                    <div class="card-body">
                        <h5 class="card-title">{{$modul['title']}}</h5>
                        <p class="card-text">{{$modul['deskripsi_modul']}}</p>
                        <p class="card-text">Diambil Pada: {{$modul['start']}}</p>
                        <hr>
                        <div class="card-text">
                            @foreach ($listtag as $tag)
                                    @if ($modul['proyek_id']==$tag['proyek_id'])
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
                        <a href="{{url("/loadDetailProyekClient/$modul[proyek_id]/h")}}" class="btn btn-primary">Lihat Lebih Detail</a>
                    </div>
                </div>
            </tr>
        @endforeach
    </table>
</center>

@endsection
