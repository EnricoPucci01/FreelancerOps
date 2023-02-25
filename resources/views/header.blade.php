<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="<?php echo asset('cssStyle.css'); ?>" type="text/css">
<head>
    @laravelPWA
    <!-- Required meta tags -->
    <meta charset="utf-8">
  <link rel="manifest" href="manifest.json" />
  <meta name="description" content="Freelancing Web">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel=“icon” href=”favicon.ico” type=“image/x-icon”>

<link rel=“shortcut icon” href=“favicon.ico” type=“image/x-icon”>
    <title>FreelancerOPS</title>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
    <!--DomPDF E-Sign-->

    <!----------------->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <center>
        <p class="mainLogo">
            @if (session()->get('role')=='freelancer')
            <a href="{{ url('/dashboardfreelancer') }}">
                <img src="{{ URL::to('images/LogoTA.png') }}" alt="FreelancerOPS Web Logo" width="350" height="80">
            </a>

            @endif
            @if (session()->get('role')=='client')
            <a href="{{ url('/dashboardClient') }}">
                <img src="{{ URL::to('images/LogoTA.png') }}" alt="FreelancerOPS Web Logo" width="350" height="80">
            </a>
            @endif
            @if (session()->get('role')=='admin')
            <a href="{{ url('/adminDashboard') }}">
                <img src="{{ URL::to('images/LogoTA.png') }}" alt="FreelancerOPS Web Logo" width="350" height="80">
            </a>
            @endif

        </p>
    </center>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark topnav">
        @if (session()->has('active'))
                @if (session()->get('role') == 'freelancer')
                    <a href="{{ url('/dashboardfreelancer') }}">Dashboard</a>
                    <a href={{ url('/browse') }}>Browse</a>
                    <a href={{ url('/listProyekFreelancer/' . session()->get('cust_id')) }}>My Projects</a>
                @endif
                @if (session()->get('role') == 'client')
                    <a href="{{ url('/dashboardClient') }}">Dashboard</a>
                    {{-- <a href="{{url("/browse")}}">Browse</a> --}}
                    <a href="{{ url('/postproject') }}">Post Project</a>
                @endif
                @if (session()->get('role') == 'admin')
                    <a href="{{ url('/adminDashboard') }}">Dashboard</a>
                    {{-- <a href="{{ url('/penarikanDanaCustomer') }}">Request Penarikan Dana</a> --}}
                    <a href={{ url('/loadTambahRekening') }}>Tambah Nomor Rekening</a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDarkDropdown"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Laporan
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark"
                                    aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item" href={{ url('/loadLaporanBulanAktif') }}>Laporan Proyek Perbulan</a></li>
                                    <li><a class="dropdown-item" href={{ url('/laporanFreelancer') }}>Laporan
                                            Freelancer</a></li>
                                    {{-- <li><a class="dropdown-item" href={{ url('/laporanClient') }}>Laporan Client</a> --}}
                            </li>
                            {{-- <li><a class="dropdown-item" href={{ url('/ketepatanPembayaran') }}>Laporan
                                            Ketepatan Pembayaran</a></li> --}}
                            <li><a class="dropdown-item" href={{ url('/loadLaporanProyekTidakBayar') }}>Laporan
                                    Pendapatan</a></li>
                            <li><a class="dropdown-item" href={{ url('/loadLaporanBelumBayar/unpaid') }}>Laporan
                                    Proyek Belum Terbayar</a></li>
                            <li><a class="dropdown-item"
                                    href={{ url('/loadFreelancerClientAktif') }}>Laporan
                                     Freelancer Tidak Aktif</a></li>
                            {{-- <li><a class="dropdown-item" href={{ url('/loadProyekBerhasil') }}>Laporan Proyek
                                            Gagal/Berhasil</a></li> --}}
                        </ul>
                        </li>
                        </ul>
                        <a href="{{ url('/logout') }}">Keluar</a>
                    </div>
                @endif

                @if (session()->get('role') != 'admin')

                     <div class="dropdown">
                      <button class="btn btn-dark dropdown-toggle"
                              type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Account
                      </button>
                      <div class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton">
                         <a class="dropdown-item"
                                            href={{ url('/loadProfil/f/' . session()->get('cust_id')) }}>Profile</a>
                                    {{-- <li><a class="dropdown-item" href={{url("/esign")}}>Upload Tanda Tangan</a></li> --}}
                                    @if (session()->get('role') == 'freelancer')
                                        <a class="dropdown-item" href={{ url('/loadTambahRekening') }}>Tambah Nomor
                                                Rekening</a>
                                    @endif
                                    <a class="dropdown-item" href={{ url('/logout') }}>Keluar</a>
                      </div>
                    </div>
                @endif
            @endif

    </nav>

    <script>
        // import {
        //     initializeApp
        // } from "/firebase/app";
        // import {
        //     getAnalytics
        // } from "/firebase/analytics";
        // import {
        //     getDatabase
        // } from "/firebase/database";
        // import {
        //     getMessaging
        // } from "/firebase/messaging/sw";
        // Import the functions you need from the SDKs you need

        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyDdoggNAdJ08JuC-Ms6CaNtuZ2IWM-cDxg",
            authDomain: "freelancerops.firebaseapp.com",
            projectId: "freelancerops",
            storageBucket: "freelancerops.appspot.com",
            messagingSenderId: "661837197172",
            appId: "1:661837197172:web:14284773ee4107fa330621",
            measurementId: "G-P9S7LLCT2J"
        };

        // Initialize Firebase
        //const app = initializeApp(firebaseConfig);
        firebase.initializeApp(firebaseConfig);
        //const analytics = getAnalytics(app);
        //const messaging = getMessaging(app);
    </script>
</head>

<body class="bg-light">
    @include('alert')
    @yield('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


    @yield('script')
</body>
</html>
