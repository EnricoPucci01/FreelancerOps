@extends('header')
@section('content')

<style type="text/css">
.pagination {
   justify-content: center;
}
</style>
<center>

    <table>
        @foreach ($listproyek as $proyek)
            <tr>
                <div class="card mt-3 mb-3" style="width: 30rem">
                    <div class="card-body">
                        <h5 class="card-title">{{$proyek->nama_proyek}}</h5>

                        @foreach ($listkategoriJob as $katJob)
                            @if ($katJob['kategorijob_id']==$proyek->kategorijob_id)
                                <h6 class="card-subtitle mb-2 text-muted">{{$katJob['judul_kategori']}}</h5>
                            @endif
                        @endforeach

                        <p class="card-text">{{$proyek['desc_proyek']}}</p>
                        <p class="card-text">Proyek Dimulai Pada {{Carbon\Carbon::parse($proyek->start_proyek)->format("d-m-Y")}}</p>
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
                        <a href="{{url("/loadDetailProyekClient/$proyek->proyek_id/c/$idCust")}}" class="btn btn-primary">Lihat Lebih Detail</a>
                    </div>
                </div>
            </tr>
        @endforeach
    </table>
    {{ $listproyek->links("pagination::bootstrap-4") }}
</center>

@endsection
