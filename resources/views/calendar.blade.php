<link rel="stylesheet" href="<?php echo asset('cssStyle.css')?>" type="text/css">
<!doctype html>
<html lang="en">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">

    <title>Freelancer OPS</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <center>
        <p class="mainLogo">
            <a href="{{url('/')}}">
                <img src="{{ URL::to('images/LogoTA.png') }}" width="350" height="80">
             </a>
         </p>
     </center>
     <nav class="navbar navbar-expand-lg topnav">
        <div class="container-fluid">
            @if (session()->has('active'))
            @if (session()->get('role')=="freelancer")
                <a href="{{url("/dashboard")}}">Dashboard</a>
                <a href={{url("/browse")}}>Browse</a>
                <a href="#news">My Projects</a>
            @endif
            @if (session()->get('role')=="client")
                <a href="{{url("/dashboardClient")}}">Dashboard</a>
                <a href="{{url("/browse")}}">Browse</a>
                <a href="{{url("/postproject")}}">Post Project</a>
            @endif
         @endif

          <button class="navbar-toggler btndrop" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown"
          aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">v</span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Account
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                  <li><a class="dropdown-item" href={{url("/loadProfil/f/".session()->get('cust_id'))}}>Profile</a></li>
                  <li><a class="dropdown-item" href={{url("/esign")}}>Upload Tanda Tangan</a></li>
                  <li><a class="dropdown-item" href={{url("/logout")}}>Keluar</a></li>
                </ul>
            </li>
            </ul>
          </div>
        </div>
      </nav>
  </head>

<body>
    <div class="container mt-5" style="max-width: 700px">
        <h2 class="h2 text-center mb-3 border-bottom pb-3">Kalender Proyek</h2>
        <div id='full_calendar_events' class='mb-3'></div>
    </div>
    <input type="hidden" id="idCust" value="{{$custId}}">
    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        var id= $('#idCust').val();
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var calendar = $('#full_calendar_events').fullCalendar({
                editable: false,
                events: "/openCal/"+id,
                displayEventTime: false,
                eventRender: function (event, element, view) {
                    event.allDay = true;
                },
                selectable: true,
                selectHelper: true,
            });
        });
    </script>
</body>
</html>
