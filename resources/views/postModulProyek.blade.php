@extends('header')
@section('content')
    <center>
        <form action={{ url('/submitpostproject') }} method="POST" style="padding: 10px" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <input type='hidden' name="tipeProyek" id="tipeProyek" value={{ session()->get('tipe_proyek') }}>
            <input type='hidden' name="hid_val" id="hid_val" value='0'>
            <input type='hidden' name="hid_val_del" id="hid_val_del" value='0'>
            <div class="card" style="width: 100%; margin-top: 20px; padding:10px">
                <div class="card-body">
                    <h3 class="card-title">Tambah Modul</h3>
                    <table>
                        <tr>
                            <td>
                                Nama: <p class="fw-bold">{{ session()->get('name_project') }}</p>
                            </td>
                            <td>
                                Tipe Proyek: <p class="fw-bold"> {{ session()->get('tipe_proyek') }}</p>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                Kategori: <p class="fw-bold">

                                    @foreach ($kategoriJob as $itemJob)
                                        @if ($itemJob->kategorijob_id == session()->get('kategorijob_project'))
                                            {{ $itemJob->judul_kategori }}
                                        @endif
                                    @endforeach

                                </p>
                            </td>
                            <td>
                                Tag: <p class="fw-bold">
                                    @foreach (session()->get('kategori_project') as $item)
                                        @foreach ($tag as $itemTag)
                                            @if ($itemTag->kategori_id == $item)
                                                {{ $itemTag->nama_kategori }}
                                            @endif
                                        @endforeach
                                    @endforeach
                                </p>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                Deadline: <p class="fw-bold"> {{Carbon\Carbon::parse(session()->get('deadline'))->format('d-m-Y') }}</p>
                            </td>
                            <td>
                                Mulai: <p class="fw-bold"> {{ Carbon\Carbon::parse(session()->get('tanggal_mulai'))->format('d-m-Y') }}</p>
                            </td>
                        </tr>
                        @if (session()->get('tipe_proyek') == 'normal')
                            <!-- total pembayaran -->
                            <tr>
                                <td>
                                    <label for="total_pembayaran" class="form-label" id="total_pembayaran_label">Total
                                        Pembayaran</label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" name="total_pembayaran" id='totalPembayaran' value="0"
                                        class="form-control" readonly>
                                </td>
                            </tr>
                        @endif

                        @if (session()->get('tipe_proyek') == 'magang')
                            <!-- total pembayaran magang-->
                            <tr id="rentang_pembayaran_label">
                                <td>
                                    <label for="rentang_pembayaran" class="form-label">Rentang Bayaran</label>
                                </td>
                            </tr>

                            <tr class="row g-3" id="rentang_pembayaran_input">
                                <td class="col-auto">
                                    <input type="text" name="rentang_pembayaran1" id='rentangPembayaran1' value="0"
                                        class="form-control" readonly>
                                </td>
                                <td class="col-auto">
                                    <p class="fw-bold fs-4"> - </p>
                                </td>

                                <td class="col-auto">
                                    <input type="text" name="rentang_pembayaran2" id='rentangPembayaran2' value="0"
                                        class="form-control" readonly>
                                </td>
                            </tr>
                        @endif
                    </table>

                    <button type='button' class='btn btn-primary form-control mt-2' id="btnok">Tambah Modul</button>
                    @if (session()->get('tipe_proyek') == 'normal')
                        <table  class="table table-striped mt-2">
                            <thead class='thead-light' >
                                <tr>
                                    <td>
                                        Nama
                                    </td>
                                    <td>
                                        Deskripsi
                                    </td>
                                    <td>
                                        Dokumen Modul
                                    </td>
                                    <td>
                                        Bayaran
                                    </td>
                                    <td>
                                        Deadline
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </thead>
                            <tbody id="modul_table">

                            </tbody>
                        </table>
                    @endif
                    @if (session()->get('tipe_proyek') == 'magang')
                        <!-- Modul Magang-->
                        <table class="table table-striped mt-2">
                            <thead class='thead-light'>
                                <tr>
                                    <td>
                                        Nama
                                    </td>
                                    <td>
                                        Deskripsi
                                    </td>
                                    <td>
                                        Dokumen
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
                                    <td>

                                    </td>
                                </tr>
                            </thead>
                            <tbody id="modul_table_Magang">

                            </tbody>
                        </table>
                    @endif

                    <button type="button" class="btn btn-primary form-control" style="margin-top: 20px"
                        data-bs-target="#modalPost" data-bs-toggle="modal">Lanjut</button>
                </div>
            </div>
            {{-- -------------------------------------------------MODAL----------------------------------------------- --}}
            {{-- Modal Post --}}
            <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPost">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalToggleLabel2">Post Proyek?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Anda akan menerbitkan proyek ini, apakah anda yakin?<br>

                            Tekan <b>Ya</b> untuk melanjutkan proses pendaftaran proyek.
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Ya</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- --------------------------------------------------------------------------------------------------- --}}
        </form>


        <script src="<?php echo asset('postProjectJS.js'); ?>"></script>
    </center>
@endsection
