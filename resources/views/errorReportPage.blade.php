@extends('header')
@section('content')

<div style="margin: 20px 20px 20px 20px">
    <H3>Laporkan error untuk progress {{$modulTitle}} pada tanggal {{$progressData->upload_time}}</H3>
    <form action="{{url("/fileError/$modulId/$freelancerId/$progressData->progress_id")}}" method="post" enctype="multipart/form-data">
        @method('POST')
        @csrf

        <label for="desc" class="form-label mt-3" >Pada Halaman Apa Error Terjadi?</label>
        <textarea class="form-control" aria-label="Deskripsikan dalam 1200 huruf" name="errPage" maxlength="1200" id='desc'></textarea>

        <label for="desc" class="form-label mt-3" >Aksi Apa Yang Anda Lakukan Sehingga Error Terjadi?</label>
        <textarea class="form-control" aria-label="Deskripsikan dalam 1200 huruf" name="errAct" maxlength="1200" id='desc'></textarea>

        <label for="desc" class="form-label mt-3" >Deskripsikan Error/Bug Yang Anda Alami?</label>
        <label for="desc" class="form-label mt-3" >Anda Dapat Menyertakan Input Yang Menyebabkan Error.</label>
        <textarea class="form-control" aria-label="Deskripsikan dalam 1200 huruf" name="errDesc" maxlength="1200" id='desc'></textarea>

        <label for="formFile" class="form-label">Masukan File, Sebagai Bukti Error</label>
        <input class="form-control" type="file" name='fileError' id="formFile">

        <center>
            <button type="submit" id='btSub' class="btn btn-success mt-3">Laporkan Error</button>
            <a href="#" class="btn btn-warning mt-3">Kembali</a>
        </center>
    </form>

</div>
@endsection
