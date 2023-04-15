@extends('header')
@section('content')
    <style type="text/css">
        .pagination {
            justify-content: center;
        }

        .grid-container {
            justify-content: flex-start;
            display: grid;
            grid-template: auto / auto auto auto;
            grid-gap: 10px;
        }

        .grid-container>div {
            text-align: center;
        }
    </style>
    <center>
        <div class="card mt-3">
            <div class="card-body">
                <table style='width:100%; height:100%; border-spacing:0;  border-collapse: collapse;'>
                    <tr>
                        <td style='width: 1%; white-space: nowrap;'>
                            @if (empty($dataProfil['foto']))
                                <img src='...' style="width: 200px; height: 240px;" class="img-thumbnail">
                            @else
                                <img src={{ asset("storage/profilePic/$dataProfil[foto]") }}
                                    style="width: 200px; height: 240px;" class="img-thumbnail">
                            @endif

                        </td>
                        <td style='width: 500px; vertical-align: top; text-align: left;'>
                            <div>
                                <h5 class="card-title ml-3">{{ $dataCust['nama'] }}</h5>
                            </div>
                            <div>
                                <p class="card-subtitle mb-3 fw-bold text-muted ml-3 ">
                                    @if (empty($dataProfil['pekerjaan']))
                                        N/A
                                    @else
                                        {{ $dataProfil['pekerjaan'] }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="card-text ml-3">
                                    @if (empty($dataProfil['deskripsi_diri']))
                                        N/A
                                    @else
                                        {{ $dataProfil['deskripsi_diri'] }}
                                    @endif
                                </p>
                            </div>
                        </td>
                        <td style='width: 14%;vertical-align: top; text-align: left;'>
                            @if ($role == 'f')
                                <a href={{ url('/loadEditProfil/' . session()->get('cust_id')) }} class='btn btn-info'
                                    style='margin-left:20%'>Edit Profile</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 1%; white-space: nowrap;">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="bi bi-telephone-fill">{{ $dataCust['nomorhp'] }}</i>
                                </li>
                                <li class="list-group-item"><i class="bi bi-envelope">{{ $dataCust['email'] }}</i></li>
                                <li class="list-group-item"><i class="bi bi-house-door">{{ $dataCust['tempat_lahir'] }}</i>
                                </li>
                            </ul>
                        </td>

                        <td
                            style="margin: auto;
                width: 100%;
                display: flex;
                    justify-content: center;">
                            <table>
                                <tr>
                                    <td>
                                        <div>
                                            <div class="mb-2">
                                                <span class="fw-bold fs-4">Rating</span>
                                            </div>

                                            <div class="mb-1">
                                                <div class="rate fs-5">
                                                    @php
                                                        $star = $bintang;
                                                        for ($i = 0; $i < 5; $i++) {
                                                            if ($star >= 1) {
                                                                echo "<i class='bi bi-star-fill' style='color:#ffc700'></i>";
                                                            } elseif ($star > 0) {
                                                                echo "<i class='bi bi-star-half' style='color:#ffc700'></i>";
                                                            } elseif ($star <= 0) {
                                                                echo "<i class='bi bi-star' style='color:gray'></i>";
                                                            }
                                                            $star--;
                                                        }
                                                    @endphp
                                                </div>
                                            </div>
                                            <div class="mb-1 mt-2">
                                                @if ((session()->get('role') == 'client' && $role == 'f') || (session()->get('role') == 'freelancer' && $role == 'v'))
                                                    <a href={{ url("/loadReviewClient/$dataCust[cust_id]") }}
                                                        class="btn btn-outline-success btn-sm"
                                                        style="padding: 0px; width: 100px">Lihat</a>
                                                @endif
                                                @if (
                                                    (session()->get('role') == 'client' && ($role == 'c' || $role == 'v')) ||
                                                        (session()->get('role') == 'freelancer' && $role == 'f'))
                                                    <a href={{ url("/loadReview/$dataCust[cust_id]") }}
                                                        class="btn btn-outline-success btn-sm"
                                                        style="padding: 0px; width: 100px">Lihat</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td>

                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                Sertifikat
            </div>
            <div class="card-body">
                <div class="grid-container">
                    @foreach ($dataSertifikat as $sertifikat)
                        <div class="card" style="width: 300px;">
                            {{-- <img src={{asset("storage/sertifikat/$sertifikat->direktori")}} class="card-img-top" alt="..."> --}}
                            <div class="card-body">
                                <h5 class="card-title">{{ $sertifikat->nama_sertifikat }}</h5>
                                <p class="card-text">{{ $sertifikat->deskripsi_sertifikat }}</p>
                                <a href={{ asset("storage/sertifikat/$sertifikat->direktori") }} download
                                    class="btn btn-primary">Download</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    @if ($role == 'f')
                        <a href={{ url('/loadUploadSertifikat') }} class="btn btn-primary mt-3">+ Tambahkan Sertifikat</a>
                    @elseif($role == 'c')
                        <a href="{{ url("/terimaApplicant/$dataCust[cust_id]/$modulId/$proyekId/$applicantId") }}"
                            class="btn btn-success">Terima</a>
                        <a href="{{ url('/') }}" class="btn btn-danger">Tolak</a>
                    @endif
                </div>
                {{ $dataSertifikat->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <style type="text/css">
            /* table, tr, td {
                        border: 1px solid black;
                    } */

            td {
                text-align: center;
            }

            .center {
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </center>
@endsection
