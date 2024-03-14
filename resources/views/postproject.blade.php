@extends('header')
@section('content')
    <center>
        <div class="card mt-3 mb-3" style="width: 70%;">
            <div class="card-body">
                <h3 class="card-title">Post Project</h3>
                <form action={{ url('/postmodul') }} method="POST" enctype="multipart/form-data">
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
                                <input class="form-control" type="file" name='dokumen' id="dokumen"
                                    accept=".pdf,.jpg,.png">
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
                                <span id="noteTipe" class="fw-bold"></span>
                                <select class="form-control" data-width="500px" id="tipeProyek"
                                    data-style="btn-default btn-lg" name="tipe_proyek">
                                    <option value="normal">Normal</option>
                                    <option value="magang">Magang</option>
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <td>Kota tempat proyek akan dilaksanakan</td>
                        </tr>
                        <tr>
                            <td>
                                <select class="selectpicker form-control" data-style="btn-default btn-lg"
                                    data-live-search="true" name="kota" id="Kota">
                                    <option disabled selected value> Kota </option>
                                    <option value="Banda Aceh">Banda Aceh</option>
                                    <option value="Langsa">Langsa</option>
                                    <option value="Lhokseumawe">Lhokseumawe</option>
                                    <option value="Sabang">Sabang</option>
                                    <option value="Subulussalam">Subulussalam</option>
                                    <option value="Denpasar">Denpasar</option>
                                    <option value="Pangkal Pinang">Pangkal Pinang</option>
                                    <option value="Cilegon">Cilegon</option>
                                    <option value="Serang">Serang</option>
                                    <option value="Tangerang Selatan">Tangerang Selatan</option>
                                    <option value="Tangerang">Tangerang</option>
                                    <option value="Bengkulu">Bengkulu</option>
                                    <option value="Yogyakarta">Yogyakarta</option>
                                    <option value="Gorontalo">Gorontalo</option>
                                    <option value="Jakarta Barat">Jakarta Barat</option>
                                    <option value="Jakarta Pusat">Jakarta Pusat</option>
                                    <option value="Jakarta Selatan">Jakarta Selatan</option>
                                    <option value="Jakarta Timur">Jakarta Timur</option>
                                    <option value="Jakarta Utara">Jakarta Utara</option>
                                    <option value="Sungai Penuh">Sungai Penuh</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Bekasi">Bekasi</option>
                                    <option value="Bogor">Bogor</option>
                                    <option value="Cimahi">Cimahi</option>
                                    <option value="Cirebon">Cirebon</option>
                                    <option value="Depok">Depok</option>
                                    <option value="Sukabumi">Sukabumi</option>
                                    <option value="Tasikmalaya">Tasikmalaya</option>
                                    <option value="Banjar">Banjar</option>
                                    <option value="Magelang">Magelang</option>
                                    <option value="Pekalongan">Pekalongan</option>
                                    <option value="Salatiga">Salatiga</option>
                                    <option value="Semarang">Semarang</option>
                                    <option value="Surakarta">Surakarta</option>
                                    <option value="Tegal">Tegal</option>
                                    <option value="Batu">Batu</option>
                                    <option value="Blitar">Blitar</option>
                                    <option value="Kediri">Kediri</option>
                                    <option value="Madiun">Madiun</option>
                                    <option value="Malang">Malang</option>
                                    <option value="Mojokerto">Mojokerto</option>
                                    <option value="Pasuruan">Pasuruan</option>
                                    <option value="Probolinggo">Probolinggo</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Pontianak">Pontianak</option>
                                    <option value="Singkawang">Singkawang</option>
                                    <option value="Banjarbaru">Banjarbaru</option>
                                    <option value="Banjarmasin">Banjarmasin</option>
                                    <option value="Palangkaraya">Palangkaraya</option>
                                    <option value="Balikpapan">Balikpapan</option>
                                    <option value="Bontang">Bontang</option>
                                    <option value="Samarinda">Samarinda</option>
                                    <option value="Tarakan">Tarakan</option>
                                    <option value="Batam">Batam</option>
                                    <option value="Tanjungpinang">Tanjungpinang</option>
                                    <option value="Bandar Lampung">Bandar Lampung</option>
                                    <option value="Metro">Metro</option>
                                    <option value="Ternate">Ternate</option>
                                    <option value="Tidoro Kepulauan">Tidoro Kepulauan</option>
                                    <option value="Ambon">Ambon</option>
                                    <option value="Tual">Tual</option>
                                    <option value="Bima">Bima</option>
                                    <option value="Mataram">Mataram</option>
                                    <option value="Kupang">Kupang</option>
                                    <option value="Sorong">Sorong</option>
                                    <option value="Jayapura">Jayapura</option>
                                    <option value="Dumai">Dumai</option>
                                    <option value="Pekanbaru">Pekanbaru</option>
                                    <option value="Makassar">Makassar</option>
                                    <option value="Palopo">Palopo</option>
                                    <option value="Parepare">Parepare</option>
                                    <option value="Palu">Palu</option>
                                    <option value="Baubau">Baubau</option>
                                    <option value="Kendari">Kendari</option>
                                    <option value="Bitung">Bitung</option>
                                    <option value="Kotamobagu">Kota Mobagu</option>
                                    <option value="Manado">Manado</option>
                                    <option value="Tomohon">Tomohon</option>
                                    <option value="Bukittinggi">Bukittinggi</option>
                                    <option value="Padang">Padang</option>
                                    <option value="Padang Panjang">Padang Panjang</option>
                                    <option value="Pariaman">Pariaman</option>
                                    <option value="Payakumbuh">Payakumbuh</option>
                                    <option value="Sawahlunto">Sawahlunto</option>
                                    <option value="Solok">Solok</option>
                                    <option value="Lubuklinggau">Lubuklinggau</option>
                                    <option value="Pagar Alam">Pagar Alam</option>
                                    <option value="Palembang">Palembang</option>
                                    <option value="Prabumulih">Prabumulih</option>
                                    <option value="Sekayu">Sekayu</option>
                                    <option value="Binjai">Binjai</option>
                                    <option value="Gunungsitoli">Gunungsitoli</option>
                                    <option value="Medan">Medan</option>
                                    <option value="Padang Sidempuan">Padang Sidempuan</option>
                                    <option value="Pematangsiantar">Pematangsiantar</option>
                                    <option value="Sibolga">Sibolga</option>
                                    <option value="Tanjungbalai">Tanjungbalai</option>
                                    <option value="Tebing Tinggi">Tebing Tinggi</option>
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
                                <select class="selectpicker form-control" data-width="500px" id="skill"
                                    data-style="btn-default btn-lg" name="kategorijob_project" data-live-search="true">
                                    @foreach ($jobKatList as $kategoriJob)
                                        <option value="{{ $kategoriJob['kategorijob_id'] }}">
                                            {{ $kategoriJob['judul_kategori'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <!-- tag Proyek -->
                        <tr>
                            <td>
                                <label for="kategori_project[]" class="form-label">Proyek ini membutuhkan
                                    keahlian?</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select class="selectpicker form-control" data-width="500px" id="skill"
                                    data-style="btn-default btn-lg" name="kategori_project[]" multiple="multiple"
                                    data-live-search="true">
                                    @foreach ($kategoriList as $kategori)
                                        <option value="{{ $kategori['kategori_id'] }}">{{ $kategori['nama_kategori'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <button type="button" class="btn btn-primary form-control" style="margin-top: 20px"
                                    data-bs-target="#modalPost" data-bs-toggle="modal">Lanjut</button>
                            </td>
                        </tr>

                    </table>
    </center>
    {{-- -------------------------------------------------MODAL----------------------------------------------- --}}
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
                    <button type="submit" id="submitPostProyek" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>
    {{-- --------------------------------------------------------------------------------------------------- --}}

    {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
    </form>
    </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var note = document.getElementById('noteTipe');
        var tipeProyek = document.getElementById("tipeProyek");

        tipeProyek.onchange = function() {
            if (tipeProyek.options[tipeProyek.selectedIndex].value == "normal") {
                note.innerHTML = "*Harga bayaran modul akan berupa harga total";
                console.log("normal");
            } else if (tipeProyek.options[tipeProyek.selectedIndex].value == "magang") {
                note.innerHTML = "*Harga bayaran modul akan berupa range harga";
                console.log("magang");
            }
        };
    </script>
@endsection
