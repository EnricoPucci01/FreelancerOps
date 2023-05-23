@extends('header')
@section('content')
    <div style="padding: 10px">
        <div class="card">
            <div class="card-header">
                <h2>Proyek Selesai</h2>
            </div>
            <div class="card-body">
                <center>
                    <table class="table table-striped">
                        <thead class="fw-bold">
                            <td>
                                Proyek
                            </td>
                            <td>
                                Modul
                            </td>
                            <td>
                                Status
                            </td>
                            <td>
                                Progress Terakhir
                            </td>
                            <td>
                                Progress
                            </td>
                            <td>
                                Action
                            </td>
                        </thead>
                        <tbody>
                            @foreach ($modulDiambil as $itemDiambil)
                                <tr>
                                    @foreach ($proyekCust as $itemProyek)
                                        @if ($itemDiambil['proyek_id'] == $itemProyek->proyek_id)
                                            <td>
                                                {{ $itemProyek->nama_proyek }}
                                            </td>
                                        @endif
                                    @endforeach
                                    @foreach ($modul as $itemModul)
                                        @if ($itemModul->modul_id == $itemDiambil['modul_id'])
                                            <td>
                                                {{ $itemModul->title }}
                                            </td>
                                        @endif
                                    @endforeach
                                    <td>
                                        @foreach ($listPembayaran as $itemPembayaran)
                                            @if ($itemDiambil['modul_id'] == $itemPembayaran['modul_id'])
                                                {{ $itemPembayaran['status'] }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($listProgress as $itemProgress)
                                            @if ($itemProgress != null && $itemProgress['modul_id'] == $itemDiambil['modul_id'])
                                                {{ $itemProgress['upload_time'] }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($listProgress as $itemProgress)
                                            @if ($itemProgress != null && $itemProgress['modul_id'] == $itemDiambil['modul_id'])
                                                {{ $itemProgress['progress'] }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($listPembayaran as $itemBayar)
                                            @foreach ($modul as $itemModul)
                                                @if (
                                                    $itemBayar['modul_id'] == $itemModul->modul_id &&
                                                        $itemModul->modul_id == $itemDiambil['modul_id'] &&
                                                        ($itemBayar['status'] == 'Completed' || $itemBayar['status'] == 'close'))
                                                    @foreach ($modul as $itemModul)
                                                        @if ($itemDiambil['modul_id'] == $itemModul->modul_id)
                                                            <a href={{ url("/loadProgress/$itemModul->modul_id/$itemDiambil[modultaken_id]") }}
                                                                class="btn btn-primary">Lihat Progress
                                                                ></a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if (
                                                    $itemBayar['modul_id'] == $itemModul->modul_id &&
                                                        $itemModul->modul_id == $itemDiambil['modul_id'] &&
                                                        $itemBayar['status'] == 'paid')
                                                    <a href={{ url("/loadProgress/$itemModul->modul_id/$itemDiambil[modultaken_id]") }}
                                                        class="btn btn-primary">Lihat Progress
                                                        ></a>

                                                    <button class="btn btn-info btn-sm"
                                                        data-bs-target={{ '#modalTutup' . $itemBayar['modul_id'] }}
                                                        data-bs-toggle="modal">Tutup Modul</button>

                                                    <div class="modal fade" tabindex="-1"
                                                        id={{ 'modalTutup' . $itemBayar['modul_id'] }} aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalToggleLabel2">
                                                                        Tutup
                                                                        Modul?</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Anda yakin akan melakukan permohonan penutupan untuk
                                                                    modul
                                                                    <b>{{ $itemModul->title }}</b>? <br>
                                                                    Jika modul ditutup maka modul akan di anggap selesai dan
                                                                    pembayaran akan di teruskan ke pihak freelancer. <br>

                                                                    Tekan <b>Ya</b> untuk melanjutkan proses penutupan
                                                                    modul.
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a class='btn btn-info btn-sm'
                                                                        href={{ url("/closeModul/$itemBayar[payment_id]") }}>Ya</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                @endif
                                                @if (
                                                    $itemBayar['modul_id'] == $itemModul->modul_id &&
                                                        $itemModul->modul_id == $itemDiambil['modul_id'] &&
                                                        ($itemBayar['status'] == 'unpaid' || $itemBayar['status'] == 'Belum Terbayar'))
                                                    @foreach ($proyekCust as $itemProyekCust)
                                                        @if ($itemModul->proyek_id == $itemProyekCust->proyek_id)
                                                            @if ($itemProyekCust->tipe_proyek == 'magang')
                                                                <a href={{ url('/loadPembayaranMagang/' . $itemModul->modul_id) }}
                                                                    class="btn btn-success">Bayar Modul
                                                                    ></a>
                                                            @endif
                                                            @if ($itemProyekCust->tipe_proyek == 'normal')
                                                                <a href={{ url('/loadPembayaran/' . $itemModul->modul_id) }}
                                                                    class="btn btn-success">Bayar Modul
                                                                    ></a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </center>
            </div>
        </div>
    </div>
@endsection
