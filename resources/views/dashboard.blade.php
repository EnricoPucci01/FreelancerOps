@extends('header')

@section('content')
    <center>
        <input type="hidden" id="hidNotif" value={{session()->get('notif')}}>
        <table style="height: 100%">
            <tr>
                <td class="tdPersonalInfo">
                    <div class="card recentProjectContainerAdminFreelancer">
                        <div class="card-body">
                            <h5 class="card-title">Proyek Terakhir</h5>
                            <div class="listRecentProject">
                                <table>

                                    <?php
                                    $i = 0;
                                    foreach ($modul as $itemModul) {
                                        $i++;
                                        if ($i <= 4) {
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
                </td>
                <td class='tdPersonalInfoAdminFreelancer'>
                    <div class="card personalInfoAdminFreelancer">
                        <div class="card-body">
                            <div class="border border-dark rounded" style="height:6.6rem; padding:5px; margin-bottom:10px">
                                <h6 class="card-subtitle mb-2 text-dark fs-4">Selamat Datang,</h6>
                                <h5 class="card-title text-dark text-uppercase fw-bold fs-2">{{ session()->get('name') }}
                                </h5>
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

                    <div class="card mt-3 infoUang">
                        <div class="card-body">
                            <div class='border border-dark bg-mute rounded' style="padding:5px;">
                                <h6 class="card-subtitle mb-1 font-weight-bold text-dark fs-4">Saldo</h6>
                                <h5 class="card-title text-dark text-uppercase fw-bold fs-2">@money($total, 'IDR', true)</h5>
                            </div>
                            <div class="mt-2 rounded" style="padding-top:10px">
                                <a href={{ url('/loadRequestTarik') }} class="btn btn-outline-dark fw-bold"
                                    style="width: 100%">Penarikan</a>
                                <a href={{ url('/histori') }} class="btn btn-outline-dark fw-bold mt-2"
                                    style="width: 100%">Histori Saldo</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </center>

    <script>
        (function() {
            var notif = document.getElementById("hidNotif");
            console.log("I am here");

            if ('setExperimentalAppBadge' in navigator) {
                isSupported('v2')
            }

            // Check if the previous API surface is supported.
            if ('ExperimentalBadge' in window) {
                isSupported('v1');
            }

            // Check if the previous API surface is supported.
            if ('setAppBadge' in navigator) {
                isSupported('v3');
            }

            // Update the UI to indicate whether the API is supported.
            function isSupported(kind) {
                console.log('supported', kind);
                setBadge();
            }

            // Wrapper to support first and second origin trial
            // See https://web.dev/badging-api/ for details.
            function setBadge() {
                console.log('set');
                if (navigator.setAppBadge) {
                    console.log('setBadge');
                    navigator.setAppBadge(notif.value);
                } else if (navigator.setExperimentalAppBadge) {
                    navigator.setExperimentalAppBadge(notif.value);
                } else if (window.ExperimentalBadge) {
                    window.ExperimentalBadge.set(notif.value);
                }
            }

            // Wrapper to support first and second origin trial
            // See https://web.dev/badging-api/ for details.
            function clearBadge() {
                if (navigator.clearAppBadge) {
                    navigator.clearAppBadge();
                } else if (navigator.clearExperimentalAppBadge) {
                    navigator.clearExperimentalAppBadge();
                } else if (window.ExperimentalBadge) {
                    window.ExperimentalBadge.clear();
                }
            }
        })();
    </script>
@endsection
