@extends('header')
@section('content')

    <center>
        <div class="card" style="width: 50%; margin-top:20px">
            <div class="card-body">
              <h5 class="card-title">Upload Sertifikat</h5>
                <form action={{url('/insertSertifikat')}} method="POST" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Judul Sertifikat</label>
                        <input class="form-control mb-2" type="text" name='judul' id="formFile">

                        <label for="formFile" class="form-label">Deskripsi dari Sertifikat</label>
                        <textarea class="form-control mb-2" type="text" name='desc_sertifikat' id="formFile"> </textarea>

                        <label for="formFile" class="form-label">Masukan Sertifikat Anda</label>
                        <input class="form-control mb-2" type="file" name='fileSertifikat' id="formFile">
                      </div>
                      <button type='submit' class="btn btn-primary">Simpan</button>
                      <a href={{url()->previous()}} class="btn btn-secondary">Kembali</a>
                </form>
            </div>
          </div>
    </center>
@endsection
