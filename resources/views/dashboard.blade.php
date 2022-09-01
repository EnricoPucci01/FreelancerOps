@extends('header')

@section('content')
    <center>

        <table style="height: 100%">
            <tr>
                <td>
                    <div class="card recentProjectContainer">
                        <div class="card-body">
                            <h5 class="card-title">Proyek Terakhir</h5>
                            <div class="listRecentProject">
                                <table>

                                    <?php
                                        $i=0;
                                        foreach ($modul as $itemModul) {
                                            $i++;
                                            if($i<4){

                                              echo " <a href='/loadDetailModulFreelancer/$itemModul[modul_id]/".Session::get('cust_id')."'>
                                                    <div class='card text-center mt-2 bg-warning'>
                                                        <div class='card-body'>
                                                            <h5 class='card-title text-light'><u>$itemModul[title]</u></h5>
                                                            <h6 class='card-subtitle text-dark'>$itemModul[start] - $itemModul[end]</h6>
                                                            <p class='card-text text-light'>$itemModul[deskripsi_modul]</p>
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
                </td>
                <td class='tdPersonalInfo'>
                    <div class="card personalInfo">
                        <div class="card-body">
                            <div class="bg-warning rounded" style="height:6.6rem; padding:5px; margin-bottom:10px">
                                <h6 class="card-subtitle mb-2 text-light fs-4">Selamat Datang,</h6>
                                <h5 class="card-title text-light text-uppercase fw-bold fs-2">{{ session()->get('name') }}
                                </h5>
                            </div>
                            <div class="bg-warning rounded" style="padding: 10px">
                                <table style="width: 100%" >
                                    <tr>
                                        {{-- <td>
                                    <a href="/portofolio"><button type="button" class="btn btn-outline-primary fw-bold" style="width: 92%">Portofolio</button></a>
                                </td> --}}
                                    </tr>
                                    <tr>
                                        <td>
                                            {{-- <a href={{url("/login")}} class="btn btn-outline-primary mb-2 mt-2 fw-bold" style="width: 92%">Obrolan</a> --}}
                                            <a href={{ url('/loadChatroom') }} class="btn btn-outline-light fw-bold mt-2 mb-2"
                                                style="width: 100%">Obrolan</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href={{ url('/loadEditCalendar') }} class="btn btn-outline-light mb-2 fw-bold"
                                                style="width: 100%">Kalender</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="{{ url('/listKontrak/pengerjaan') }}"
                                                class="btn btn-outline-light fw-bold" style="width: 100%">List Kontrak</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="card mt-3 infoUang">
                        <div class="card-body">
                            <div class='border border-light bg-warning rounded' style="padding:5px;">
                                <h6 class="card-subtitle mb-1 font-weight-bold text-light fs-4">Saldo</h6>
                                <h5 class="card-title text-light text-uppercase fw-bold fs-2">@money($total, 'IDR', true)</h5>
                            </div>
                            <div class="mt-2 bg-warning rounded" style="padding: 10px">
                                <a href={{ url('/loadRequestTarik') }} class="btn btn-outline-light fw-bold"
                                    style="width: 100%">Penarikan</a>
                                <a href={{ url('/histori') }} class="btn btn-outline-light fw-bold mt-2"
                                    style="width: 100%">Histori Saldo</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </center>
@endsection
