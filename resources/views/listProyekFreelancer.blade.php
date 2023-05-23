@extends('header')
@section('content')
    <style type="text/css">
        .pagination {
            justify-content: center;
        }

        .sidebar {
            margin: 0;
            padding: 0;
            width: 200px;
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
        <div class="sidebar">
            <div style="background:#1b63a1; height:15%; color:white; font-weight:bold; font-size:20px" class="center">
                <p>Sort</p>
            </div>
            <a href={{ url('/listProyekFreelancer/deadline') }} class={{  $mode == "deadline" ? 'active' : "" }}>Tanggal
                Deadline</a>
            <a href={{ url('/listProyekFreelancer/tglmulai') }} class={{  $mode == "tglmulai" ? 'active' : "" }}>Tanggal Mulai</a>
            <a href={{ url('/listProyekFreelancer/reset') }}>Reset</a>
        </div>
        @if (count($listproyek) > 0)
            <table>
                @foreach ($listproyek as $proyek)
                    <tr>
                        <div class="card mb-2" style="width: 30rem; margin-top: 20px">
                            <div class="card-header">
                                <h5 class="card-title">{{ $proyek->title }}</h5>
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
