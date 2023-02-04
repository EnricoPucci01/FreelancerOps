@extends('header')
@section('content')
    <div style="padding: 10px">
        <div class="card mt-3" style="padding: 20px">
            <div class="card-title">
                <h1>
                    Laporan Freelancer Tidak Aktif
                </h1>
            </div>
            <table class="table table-striped">
                <thead class="fw-bold">
                    <tr>
                        <td style="text-align: center">
                            Nama
                        </td>
                        <td style="text-align: center">
                            Email
                        </td>
                        <td style="text-align: center">
                            Nomor HP
                        </td>
                        <td style="text-align: center">
                            Proyek Terakhir Di Ambil
                        </td>
                        <td style="text-align: center">
                            Kontak
                        </td>
                        {{-- <td style="text-align: center">
                            Quisioner
                        </td> --}}
                        <td style="text-align: center">
                            Aktifkan/Non-Aktifkan
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataFreelancerClient as $itemFreelancer)
                        <tr>
                            <td style="text-align: center ;width:10%">
                                <a
                                    href={{ url("/detailLaporanFreelancerAktif/$itemFreelancer[id]") }}>{{ $itemFreelancer['nama'] }}</a>
                            </td>
                            <td style="text-align:center">
                                {{ $itemFreelancer['email'] }}
                            </td>
                            <td style="text-align: center">
                                {{ $itemFreelancer['hp'] }}
                            </td>
                            <td style="text-align: center">
                                @if ($itemFreelancer['lastProject'] == -1)
                                    Belum Pernah Mengambil Proyek
                                @else
                                    {{ $itemFreelancer['lastProject'] }} Hari Lalu
                                @endif
                            </td>
                            <td style="text-align: center">
                                <a href={{ url("/sendEmail/$itemFreelancer[email]/inactive") }}
                                    class="btn btn-warning">E-Mail</a>
                            </td>
                            {{-- <td style="text-align: center">
                                <button type="button" class="btn btn-primary" id={{"myBtnQuisioner".$itemFreelancer['hp']}}
                                        data-bs-target={{"#modalSendQuisioner".$itemFreelancer['hp']}} data-bs-toggle="modal">Quisioner</button>
                            </td> --}}
                            <td style="text-align: center">
                                @if ($itemFreelancer['deleteStat'] != null)
                                    <button type="button" class="btn btn-success" id={{"myBtnAktif".$itemFreelancer['hp']}}
                                        data-bs-target={{"#modalTarikAktif".$itemFreelancer['hp']}} data-bs-toggle="modal">Aktifkan</button>
                                @else
                                    <button type="button" class="btn btn-danger" id={{"myBtn".$itemFreelancer['hp']}}
                                        data-bs-target={{"#modalTarik".$itemFreelancer['hp']}} data-bs-toggle="modal">Non-Aktifkan</button>
                                @endif

                            </td>
                        </tr>

                        <div class="modal fade" tabindex="-1"aria-hidden="true" id={{"modalTarik".$itemFreelancer['hp']}}>
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel2">Non-Aktifkan Akun?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Anda akan melakukan Non-Aktifasi akun freelancer.

                                        Tekan <b>Ya</b> untuk melanjutkan proses Non-Aktifasi.
                                    </div>
                                    <div class="modal-footer">
                                        <a href={{ url("/nonAktifkanAkun/$itemFreelancer[email]") }} class="btn btn-danger">Ya</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1"aria-hidden="true" id={{"modalTarikAktif".$itemFreelancer['hp']}}>
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel2">Aktifkan Akun?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Anda akan melakukan Aktifasi akun freelancer.

                                        Tekan <b>Ya</b> untuk melanjutkan proses Non-Aktifasi.
                                    </div>
                                    <div class="modal-footer">
                                        <a href={{ url("/aktifkanAkun/$itemFreelancer[email]") }} class="btn btn-success">Ya</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="modal fade" tabindex="-1"aria-hidden="true" id={{"modalSendQuisioner".$itemFreelancer['hp']}}>
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel2">Kirim Quisioner?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Anda akan mengirimkan quisioner kepada freelancer.

                                        Tekan <b>Ya</b> untuk melanjutkan.
                                    </div>
                                    <div class="modal-footer">
                                        <a href={{ url("/sendEmail/$itemFreelancer[email]/quisioner") }} class="btn btn-success">Ya</a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- <script type="text/javascript">
        let email = document.getElementById('emailFreelancer');
        let bank = document.getElementById('bank');
        let penarikan = document.getElementById('totalPenarikan');
        let btn = document.getElementById('myBtn');
        var arrBank = @json($dataRekening);

        btn.addEventListener('click', function() {


        });
    </script> --}}
@endsection
