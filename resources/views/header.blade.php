<link rel="stylesheet" href="<?php echo asset('cssStyle.css'); ?>" type="text/css">

<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
    <!--DomPDF E-Sign-->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
        rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
    <!----------------->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <center>
        <p class="mainLogo">
            <a href="{{ url('/') }}">
                <img src="{{ URL::to('images/LogoTA.png') }}" width="350" height="80">
            </a>
        </p>
    </center>

    <nav class="navbar navbar-expand-lg topnav">
        <div class="container-fluid">
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
                    <a href="{{ url('/penarikanDanaCustomer') }}">Request Penarikan Dana</a>
                    <a href={{ url('/loadTambahRekening') }}>Tambah Nomor Rekening</a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Laporan
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark"
                                    aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item" href={{ url('/loadLaporanBulanAktif') }}>Laporan Bulan
                                            Aktif</a></li>
                                    <li><a class="dropdown-item" href={{ url('/laporanFreelancer') }}>Laporan
                                            Freelancer</a></li>
                                    {{-- <li><a class="dropdown-item" href={{ url('/laporanClient') }}>Laporan Client</a> --}}
                                    </li>
                                    {{-- <li><a class="dropdown-item" href={{ url('/ketepatanPembayaran') }}>Laporan
                                            Ketepatan Pembayaran</a></li> --}}
                                    <li><a class="dropdown-item" href={{ url('/loadLaporanProyekTidakBayar') }}>Laporan
                                            Pendapatan</a></li>
                                    <li><a class="dropdown-item" href={{ url('/loadFreelancerAktif') }}>Laporan
                                            Freelancer Aktif</a></li>
                                    <li><a class="dropdown-item" href={{ url('/loadProyekBerhasil') }}>Laporan Proyek
                                            Gagal/Berhasil</a></li>
                                </ul>
                            </li>
                        </ul>
                        <a href="{{ url('/logout') }}">Keluar</a>
                    </div>
                @endif

                @if (session()->get('role') != 'admin')
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Account
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark"
                                    aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item"
                                            href={{ url('/loadProfil/f/' . session()->get('cust_id')) }}>Profile</a></li>
                                    {{-- <li><a class="dropdown-item" href={{url("/esign")}}>Upload Tanda Tangan</a></li> --}}
                                    <li><a class="dropdown-item" href={{ url('/calendarId') }}>Upload Calendar Id</a>
                                    </li>
                                    @if (session()->get('role') == 'freelancer')
                                        <li><a class="dropdown-item" href={{ url('/loadTambahRekening') }}>Tambah Nomor Rekening</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href={{ url('/logout') }}>Keluar</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                @endif
            @endif


        </div>
    </nav>


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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
</body>

</html>
