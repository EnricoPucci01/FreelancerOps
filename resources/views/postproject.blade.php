@extends('header')
@section('content')
<center>
    <div class="card mt-3 mb-3" style="width: 70%;" >
        <div class="card-body">
          <h3 class="card-title">Post Project</h3>
          <form action={{url("/postmodul")}} method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
              <table>
                  <!-- Nama -->
                  <tr>
                    <td>
                        <label for="name_project" class="form-label">Nama</label>
                    </td>
                  </tr>
                  <tr>
                      <td>
                        <input type="text" name="name_project" class="form-control">
                      </td>
                  </tr>
                    <!-- deskripsi -->
                  <tr>
                      <td>
                            <label for="desc_project" class="form-label">Deskripsi</label>
                      </td>
                  </tr>
                  <tr>
                      <td>
                        <textarea type="text" name="desc_project" class="form-control"></textarea>
                      </td>
                  </tr>
                  <tr>
                    <td>
                        <label for="dokumen" class="form-label">Dokumen mengenai proyek (PDF, JPG, PNG)</label>
                    </td>
                  </tr>
                  <tr>
                    <td>
                        <input class="form-control" type="file" name='dokumen' id="dokumen" accept=".pdf,.jpg,.png">
                    </td>
                  </tr>
                <!-- Tipe Proyek -->
                <tr>
                    <td>
                        <label for="tipe_proyek" class="form-label">Tipe Proyek</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select class="form-control" data-width="500px" id="tipeProyek" data-style="btn-default btn-lg" name="tipe_proyek">
                            <option value="normal" selected>Normal</option>
                            <option value="magang">Magang</option>
                        </select>
                    </td>
                </tr>

                <!-- Deadline -->
                <tr>
                    <td>
                        <label for="deadline" class="form-label">Batas akhir proyek</label>
                    </td>
                </tr>
                <tr>
                    <td>
                      <input type="date" name="deadline" class="form-control">
                    </td>
                </tr>

                <!-- tanggal_mulai -->
                <tr>
                    <td>
                        <label for="tanggal_mulai" class="form-label">Tanggal mulai proyek</label>
                    </td>
                </tr>
                <tr>
                    <td>
                      <input type="date" name="tanggal_mulai" class="form-control">
                    </td>
                </tr>

                <!-- Kategori job Proyek -->
                <tr>
                    <td>
                        <label for="kategori_project[]" class="form-label">Ini adalah Proyek?</label>
                    </td>
                </tr>
                <tr>
                    <td>
                      <select class="selectpicker form-control" data-width="500px" id="skill" data-style="btn-default btn-lg" name="kategorijob_project" data-live-search="true">
                          @foreach ($jobKatList as $kategoriJob)
                            <option value="{{$kategoriJob['kategorijob_id']}}">{{$kategoriJob['judul_kategori']}}</option>
                          @endforeach
                        </select>
                    </td>
                </tr>

                <!-- tag Proyek -->
                <tr>
                    <td>
                        <label for="kategori_project[]" class="form-label">Proyek ini membutuhkan keahlian?</label>
                    </td>
                </tr>
                <tr>
                    <td>
                      <select class="selectpicker form-control" data-width="500px" id="skill" data-style="btn-default btn-lg" name="kategori_project[]" multiple="multiple" data-live-search="true">
                          @foreach ($kategoriList as $kategori)
                            <option value="{{$kategori['kategori_id']}}">{{$kategori['nama_kategori']}}</option>
                          @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <button type="button" class="btn btn-primary form-control" style="margin-top: 20px" data-bs-target="#modalPost" data-bs-toggle="modal">Lanjut</button>
                    </td>
                </tr>

            </table>
        </center>
            {{---------------------------------------------------MODAL-------------------------------------------------}}
                    {{-- Modal Post --}}
                    <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPost">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel2">Lanjutkan Proses?</h5>
                                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah data proyek sudah sesuai dengan keinginan anda?<br>

                                    Tekan <b>Ya</b> untuk melanjutkan proses pendaftaran proyek.
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Ya</button>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-------------------------------------------------------------------------------------------------------}}

              {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
          </form>
        </div>
      </div>
@endsection
