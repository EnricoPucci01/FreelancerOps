@extends('header')
@section('content')
    <div style="padding: 10px">
        <div class="card">
            <div class="card-header">
                <h2>Proyek Dalam Pengerjaan</h2>
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
                                        @foreach ($modul as $itemModul)
                                            @if ($itemDiambil['modul_id'] == $itemModul->modul_id)
                                                <a href={{ url("/loadProgress/$itemModul->modul_id/$itemDiambil[modultaken_id]") }}
                                                    class="btn btn-primary">Lihat Progress
                                                    ></a>
                                            @endif
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
