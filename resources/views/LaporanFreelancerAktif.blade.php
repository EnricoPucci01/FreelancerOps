@extends('header')
@section('content')
    <div class="card mt-3" style="padding: 20px">
        <div class="card-title">
            <h1>
                @if ($custType == 'Client')
                    Laporan Client Aktif
                @else
                    Laporan Freelancer Aktif
                @endif

            </h1>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href={{ url('/loadFreelancerClientAktif/Freelancer') }}
                    class="{{ $custType == 'Freelancer' ? 'nav-link active' : 'nav-link' }}" id="home-tab" role="tab"
                    aria-selected={{ $custType == 'Freelancer' ? 'true' : 'false' }}>Freelancer</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href={{ url('/loadFreelancerClientAktif/Client') }}
                    class="{{ $custType == 'Client' ? 'nav-link active' : 'nav-link' }}" id="profile-tab" role="tab"
                    aria-selected={{ $custType == 'Client' ? 'true' : 'false' }}>Client</a>
            </li>
        </ul>
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
                        @if ($custType == 'Client')
                            Proyek Terakhir Di Post
                        @else
                            Proyek Terakhir Di Ambil
                        @endif

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
                                @if ($custType == 'Client')
                                    Belum Pernah Post Proyek
                                @else
                                    Belum Pernah Mengambil Proyek
                                @endif
                            @else
                                {{ $itemFreelancer['lastProject'] }} Hari Lalu
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
