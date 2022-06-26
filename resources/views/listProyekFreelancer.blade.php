@extends('header')
@section('content')
<style type="text/css">
    .pagination {
       justify-content: center;
    }
</style>
<center>
    @if (count($listproyek)>0)
        <table>
            @foreach ($listproyek as $proyek)
                <tr>
                    <div class="card mb-2" style="width: 30rem; margin-top: 20px">
                        <div class="card-body">
                            <h5 class="card-title">{{$proyek->title}}</h5>
                            <p class="card-text">{{$proyek->deskripsi_modul}}</p>
                            <hr>
                                <p class="card-text">{{$proyek->start}} - {{$proyek->end}}</p>
                            <hr>
                            <a href="{{url("/loadDetailModulFreelancer/$proyek->modul_id/$custId")}}" class="btn btn-primary btn-sm">Lihat Detail</a>
                            <a href={{url("/loadError/$proyek[modul_id]")}} class="btn btn-warning btn-sm">Lihat Laporan Error</a>
                        </div>
                    </div>
                </tr>
            @endforeach
        </table>
    @else
    <div style="text-align:center; margin-top: 15%;">
        <h1>Anda Tidak Memiliki Proyek ...</h1>
    </div>

    @endif
    {{ $listproyek->links("pagination::bootstrap-4") }}
</center>

@endsection
