@extends('header')
@section('content')
    <style type="text/css">
        .pagination {
            justify-content: center;
        }

        .sidebar {
            margin: 0;
            padding: 0;
            width: 220px;
            background-color: #f1f1f1;
            position: absolute;
            height: 35%;
            overflow: auto;
        }

        .sidebar a {
            display: block;
            color: black;
            padding: 16px;
            text-decoration: none;
        }

        .sidebar a.active {
            background-color: #e7880d;
            color: white;
            font-weight: bold;
        }

        .sidebar a:hover:not(.active) {
            background-color: #e7880d;
            color: white;
        }

        div.content {
            margin-left: 200px;
            padding: 1px 16px;
            height: 1000px;
        }

        @media screen and (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                float: left;
            }

            div.content {
                margin-left: 0;
            }
        }

        @media screen and (max-width: 400px) {
            .sidebar a {
                text-align: center;
                float: none;
            }
        }

        .center {
            width: 100%;
            padding: 2px;
        }
    </style>
    <center>
        <div class="sidebar" style="height: 170px; text-align:left;">
            <div style="background:#1b63a1; height:20%; color:white; font-weight:bold; font-size:20px;padding-left:10px"
                class="center">
                <p>Sort</p>
            </div>
            <form action={{ url('/listProyekFreelancer/sort') }} style="padding-left:10px">

                <input type="radio" id="tanggalDeadline" name="rbsort" value="tanggalDeadline"
                    {{ $checkedRb == 'tanggalDeadline' ? 'checked' : '' }}>
                <label for="tanggalDeadline">Deadline terdekat</label> <br>

                <input type="radio" id="tanggalMulai" name="rbsort" value="tanggalMulai"
                    {{ $checkedRb == 'tanggalMulai' ? 'checked' : '' }}>
                <label for="tanggalMulai">Tanggal mulai terdekat</label> <br>
                <div style="margin-top: 20px">
                    <table>
                        <tr>
                            <td>
                                <button type="submit" name="btnSort" class="btn btn-success">
                                    Sortir
                                </button>
                            </td>
                            <td>
                                <a href={{ url('/listProyekFreelancer/default') }}>Reset</a>
                            </td>
                        </tr>
                    </table>


                </div>
            </form>
        </div>
        @if (count($listproyek) > 0)
            <table>
                @foreach ($listproyek as $proyek)
                    <tr>
                        <div class="card mb-2" style="width: 30rem; margin-top: 20px">
                            <div class="card-header">
                                <h5 class="card-title">{{ $proyek->title }}</h5>

                                @foreach ($allproyek as $proj)
                                    @if ($proj->proyek_id == $proyek->proyek_id)
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $proj->nama_proyek }}</h5>

                                            {{-- -------------------------------------------------MODAL----------------------------------------------- --}}
                                            {{-- Modal Post --}}
                                            <div class="modal fade" tabindex="-1"aria-hidden="true"
                                                id="modalPost{{ $proyek->modul_id }}" style="text-align: left">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form
                                                        action="{{ url("/updateProgress/$proyek[modul_id]/$proj[tipe_proyek]") }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalToggleLabel2">
                                                                    Selesaikan Modul {{ $proyek->title }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <label for="formFile" class="form-label">Masukan File
                                                                    Proyek</label>
                                                                <input class="form-control" type="file" name='fileModul'
                                                                    id="formFile">
                                                                <label for="desc" class="form-label mt-3">Deskripsikan
                                                                    Progress Anda</label>
                                                                <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="progDesc" maxlength="1000"
                                                                    id='desc'></textarea>
                                                                <input type="checkbox" class="form-check-input ml-0"
                                                                    id="exampleCheck1" name='cb[]' value='finish'
                                                                    checked>
                                                                <label class="form-check-label ml-3 fw-bold"
                                                                    for="exampleCheck1">&nbsp;Modul Sudah
                                                                    Selesai</label>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Selesaikan</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- ---------------------------------------------------------------------------------------------------- --}}
                                    @endif
                                @endforeach
                            </div>
                            <div class="card-body" style="text-align: start">
                                <h6>Progress Terakhir</h6>
                                @foreach ($listProgress as $progress)
                                    @if ($progress != null)
                                        @if ($proyek['modul_id'] == $progress['modul_id'])
                                            <p>Tanggal Upload: <span
                                                    style="font-weight:bold;">{{ Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $progress['upload_time'])->format('d-m-Y H:i:s') }}
                                                </span></p>
                                            <h6>Deskripsi Progress</h6>
                                            <p>{{ $progress['progress'] }}</p>
                                        @endif
                                    @endif
                                @endforeach

                                <hr>
                                <p class="card-text">Proyek Dimulai Pada: <span
                                        style="font-weight:bold;">{{ Carbon\Carbon::parse((string) $proyek->start)->format('d-m-Y') }}</span>
                                </p>
                                <p class="card-text">Proyek Berakhir Pada: <span
                                        style="font-weight:bold;">{{ Carbon\Carbon::parse((string) $proyek->end)->format('d-m-Y') }}</span>
                                </p>
                                <hr>
                                <a href="{{ url("/loadDetailModulFreelancer/$proyek->modul_id/$custId") }}"
                                    class="btn btn-primary btn-sm">Lihat Detail</a>
                                <a href={{ url("/loadError/$proyek[modul_id]") }} class="btn btn-warning btn-sm">Lihat
                                    Laporan Error</a>
                                @if (in_array($proyek->modul_id, $listPayment))
                                    <button type="button" data-bs-target="#modalPost{{ $proyek->modul_id }}"
                                        data-bs-toggle="modal" disabled class="btn btn-success btn-sm">Selesaikan Modul
                                    </button>
                                @else
                                <button type="button" data-bs-target="#modalPost{{ $proyek->modul_id }}"
                                    data-bs-toggle="modal" class="btn btn-success btn-sm">Selesaikan Modul
                                </button>
                                @endif
                            </div>
                        </div>
                    </tr>
                @endforeach
            </table>
        @else
            <div style="text-align:center; margin-top: 15%;">
                <h1>Anda Tidak Memiliki Proyek ...</h1>
            </div>
        @endif
        {{ $listproyek->links('pagination::bootstrap-4') }}
    </center>
@endsection
