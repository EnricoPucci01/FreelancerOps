@extends('header')
@section('content')

<center>
    <a class="btn btn-primary" style="width: 30%; margin: 20px 20px 20px 20px;" href="/tambahportofolio">Tambah CV</a>
</center>

    @foreach ($dataCv as $cv)
        <div style="margin: 20px 20px 20px 20px;">
            <div class="card" style="width: 30%; margin-right: 10px;">
                <!-- <img src="..." class="card-img-top" alt="..."> -->
                <div class="card-body">
                <h5 class="card-title">CV {{$cv['created_at']}}</h5>
                <a href="{{url("previewcv/$cv[direktori]")}}" class="btn btn-primary">Lihat</a>
                <a href="#" class="btn btn-primary">download</a>
                <a href="#" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    @endforeach

@endsection
