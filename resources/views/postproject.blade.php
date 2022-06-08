@extends('header')
@section('content')
<center>
    <div class="card" style="width: 70%; margin-top: 20px;" >
        <div class="card-body">
          <h3 class="card-title">Post Project</h5>
          <form action={{url("/submitpostproject")}} method="POST">
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
                        <textarea type="text" name="desc_project" class="form-control"> </textarea>
                      </td>
                  </tr>

                <!-- total pembayaran -->
                <tr>
                    <td>
                        <label for="total_pembayaran" class="form-label" id="total_pembayaran_label">Total Pembayaran</label>
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="number" name="total_pembayaran" id='totalPembayaran' value="0" class="form-control" readonly>
                    </td>
                </tr>
                <!-- total pembayaran magang-->
                <tr style="visibility: hidden; display:none" id="rentang_pembayaran_label">
                    <td>
                        <label for="rentang_pembayaran" class="form-label">Rentang Bayaran</label>
                    </td>
                </tr>

                <tr class="row g-3" style="visibility: hidden; display:none"  id="rentang_pembayaran_input">
                    <td class="col-auto">
                        <input type="number" name="rentang_pembayaran1" id='rentangPembayaran1' value="0" class="form-control" readonly>
                    </td>
                    <td class="col-auto">
                        <p class="fw-bold fs-4"> - </p>
                    </td>

                    <td class="col-auto">
                        <input type="number" name="rentang_pembayaran2" id='rentangPembayaran2' value="0" class="form-control" readonly>
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
                      <select class=" selectpicker form-control" data-width="500px" id="skill" data-style="btn-default btn-lg" name="kategori_project[]" multiple="multiple" data-live-search="true">
                          @foreach ($kategoriList as $kategori)
                            <option value="{{$kategori['kategori_id']}}">{{$kategori['nama_kategori']}}</option>
                          @endforeach
                        </select>
                    </td>
                </tr>

                <!-- Modul -->
                <tr>
                    <td>
                        <input type='hidden' name="hid_val" id="hid_val" value='0'>
                        <button type='button' class='btn btn-primary form-control mt-2' id="btnok">Tambah Modul</button>
                    </td>
                </tr>

              </table>
              <table id="modul_table" class="table table-striped mt-2">
                  <thead class='thead-light'>
                    <tr>
                        <td>
                            Nama
                        </td>
                        <td>
                            Deskripsi
                        </td>
                        <td>
                            Bayaran
                        </td>
                        <td>
                            Deadline
                        </td>
                    </tr>
                  </thead>
              </table>
                <!-- Modul Magang-->
              <table id="modul_table_Magang" class="table table-striped mt-2" style="visibility: hidden; display:none">
                <thead class='thead-light'>
                  <tr>
                      <td>
                          Nama
                      </td>
                      <td>
                          Deskripsi
                      </td>
                      <td>
                          Rentang Bayaran Dari
                      </td>
                      <td>
                          Rentang Bayaran Hingga
                      </td>
                      <td>
                          Deadline
                      </td>
                  </tr>
                </thead>
            </table>
              <button type="button" class="btn btn-primary" style="margin-top: 10px" data-bs-target="#modalPost" data-bs-toggle="modal">Post</button>

            {{---------------------------------------------------MODAL-------------------------------------------------}}
                    {{-- Modal Post --}}
                    <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPost">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel2">Post Proyek?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Anda akan mendaftarkan proyek baru. Apakah proyek sudah sesuai dengan kebutuhan anda?<br>

                                Tekan <b>Ya</b> untuk melanjutkan proses pendaftaran proyek.
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Ya</button>
                            </div>
                        </div>
                    </div>
                {{-------------------------------------------------------------------------------------------------------}}

              {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
          </form>
        </div>
      </div>
</center>
<script src="<?php echo asset('postProjectJS.js')?>"></script>
@endsection
