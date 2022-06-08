@extends('header')
@section('content')

<div style="margin: 20px 20px 20px 20px">
    <form action="{{url("/kirimPesan")}}" method="post" enctype="multipart/form-data">
        @method('POST')
        @csrf
        <label for="formFile" class="form-label">Kirim Pesan Ke</label>
        <input class="form-control" type="text" name='tujuan' id="formFile">
        <label for="desc" class="form-label mt-3" >Pesan Anda</label>
        <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="pesan" maxlength="1200" id='desc'></textarea>

        <center>
            <button type="submit" id='btSub' class="btn btn-success mt-3">Kirim</button>
            <a href={{("/loadChatroom")}} class="btn btn-warning mt-3">Kembali</a>
        </center>
    </form>

</div>
@endsection
