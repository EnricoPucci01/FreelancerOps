@extends('header')

@section('content')
<center>
    <div>
        <div class="tdPersonalInfo">
            <div class="card recentProjectContainerAdminFreelancer">
                <div class="card-body">
                    <h5 class="card-title">Proyek Terakhir</h5>
                    <div class="listRecentProject">
                        <table>
                            <?php
                            $i = 0;
                            foreach ($modul as $itemModul) {
                                $i++;
                                if ($i < 3) {
                                    echo " <a href='/loadDetailModulFreelancer/$itemModul[modul_id]/" .
                                        Session::get('cust_id') .
                                        "'>
                                                                                                                                                        <div class='card text-center mt-2 bg-light'>
                                                                                                                                                            <div class='card-body'>
                                                                                                                                                                <h5 class='card-title text-dark'><u>$itemModul[title]</u></h5>
                                                                                                                                                                <h6 class='card-subtitle text-secondary'>" .
                                        Carbon\Carbon::parse($itemModul['start'])->format('d-m-Y') .
                                        ' - ' .
                                        Carbon\Carbon::parse($itemModul['end'])->format('d-m-Y') .
                                        "</h6>
                                                                                                                                                                <p class='card-text text-dark'>$itemModul[deskripsi_modul]</p>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </a>";
                                }
                            }
                            ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class='tdPersonalInfo' >
            <div class="card personalInfoAdminFreelancer">
                <div class="card-body" style="text-align: left">
                    <div class="border border-dark rounded" style="height:7rem; padding:5px; ">
                        <h6 class="card-subtitle text-dark fs-4">Selamat Datang,</h6>
                        <h5 class="card-title text-dark text-uppercase fw-bold fs-2">{{ session()->get('name') }}</h5>
                        <p class="text-dark fs-5">Proyek Selesai: {{$modulSelesai}}</p>
                    </div>
                    <div class="rounded">
                        <table style="width: 100%">
                            <tr>
                                {{-- <td>
                            <a href="/portofolio"><button type="button" class="btn btn-outline-primary fw-bold" style="width: 92%">Portofolio</button></a>
                        </td> --}}
                            </tr>
                            <tr>
                                <td>
                                    {{-- <a href={{url("/login")}} class="btn btn-outline-primary mb-2 mt-2 fw-bold" style="width: 92%">Obrolan</a> --}}
                                    <a href={{ url('/loadChatroom') }}
                                        class="btn btn-outline-dark fw-bold mt-2 mb-2"
                                        style="width: 100%">Obrolan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href={{ url('/loadEditCalendar') }} class="btn btn-outline-dark mb-2 fw-bold"
                                        style="width: 100%">Tambah Acara</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="{{ url('/listKontrak/pengerjaan') }}"
                                        class="btn btn-outline-dark fw-bold" style="width: 100%">List Kontrak</a>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="card mt-2 infoUang">
            <div class="card-body">
                <div class='border border-dark bg-mute rounded' style="padding:5px;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width:42%;">
                                <h6 class="card-subtitle mb-1 font-weight-bold text-dark fs-5">Saldo</h6>
                                <h5 class="card-title text-dark text-uppercase fw-bold fs-4">@money($total, 'IDR', true)</h5>
                            </td>
                            <td style="border-right:2px solid black;">
                            </td>
                            <td>
                                <h6 class="ml-2 card-subtitle mb-1 font-weight-bold text-dark fs-6">Pendapatan 30Hari Terakhir</h6>
                                <h5 class="ml-2 card-title text-dark text-uppercase fw-bold fs-4">@money((is_null($pendapatanBulanan))?0:$pendapatanBulanan, 'IDR', true)</h5>
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="rounded" style="padding-top:10px">
                    <a href={{ url('/loadRequestTarik') }} class="btn btn-outline-dark fw-bold"
                        style="width: 100%">Penarikan</a>
                    <a href={{ url('/histori/noFilter') }} class="btn btn-outline-dark fw-bold mt-2"
                        style="width: 100%">Histori Saldo</a>
                </div>
            </div>
        </div>
    </div>
</center>

    <script>
        (function() {
            navigator.serviceWorker.ready.then(function(sw) {
                return sw.sync.register('sync');
            });
        })();
    </script>
@endsection
