@extends('header')
@section('content')
<center>
    <div class="card mb-3" style="width: 50rem; margin-top: 20px;" >
        <div class="card-body">
          <h3 class="card-title">Edit Profile</h5>
          <form action={{url('/submiteditprofil/'.session()->get('cust_id'))}} method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
              <table>
                  <!-- Foto -->
                  <tr>
                    <td>
                        <label for="profile_foto" class="form-label">Foto</label>
                    </td>
                  </tr>
                  <tr>
                      <td>
                        <div class="input-group mb-2">
                            <input type="file" class="form-control" name='profile_foto'>
                          </div>
                      </td>
                  </tr>
                  <!-- Nama -->
                  <tr>
                    <td>
                        <label for="profile_nama" class="form-label">Nama</label>
                    </td>
                  </tr>
                  <tr>
                      <td>
                        <input type="text" name="profile_nama" value="{{$dataCust['nama']}}" class="form-control mb-2">
                      </td>
                  </tr>
                  <!-- nomor HP -->
                  <tr>
                    <td>
                        <label for="profile_hp" class="form-label">Nomor HP</label>
                    </td>
                  </tr>
                  <tr>
                      <td>
                        <input type="number" name="profile_hp" value="{{$dataCust['nomorhp']}}" class="form-control mb-2">
                      </td>
                  </tr>
                    <!-- pekerjaan -->
                    <tr>
                        <td>
                            <label for="profile_job" class="form-label">Pekerjaan Professional</label>
                        </td>
                    </tr>
                    <tr>
                        @if (empty($dataProfil['pekerjaan']))
                            <td>
                                <input type="text" name="profile_job" class="form-control mb-2">
                            </td>
                        @else
                            <td>
                                <input type="text" name="profile_job" value="{{$dataProfil['pekerjaan']}}"   class="form-control mb-2">
                            </td>
                        @endif

                    </tr>

                    <!-- deskripsi -->
                  <tr>
                      <td>
                            <label for="profile_desc" class="form-label">Deskripsi</label>
                      </td>
                  </tr>
                  <tr>
                    <td>
                        <textarea type="text" name="profile_desc" value="{{$dataProfil['deskripsi_diri']}}" class="form-control mb-2"> {{$dataProfil['deskripsi_diri']}}</textarea>
                    </td>
                  </tr>
              </table>
              <button type="submit" class="btn btn-primary" style="margin-top: 10px">Edit</button>
          </form>
        </div>
      </div>
</center>
@endsection
