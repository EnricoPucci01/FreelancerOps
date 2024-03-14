<!DOCTYPE html>
<html lang="en">

<head>
    @laravelPWA
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <style tyle="text/css">
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .wrapper {
            align-items: center;
            height: 100%;
            width: 100%;
        }

        h1 {
            color: #e7880d;
            font-family: arial;
            font-weight: bold;
            font-size: 50px;
            letter-spacing: 5px;
            line-height: 1rem;
            text-shadow: 0 0 3px #e7880d;
        }

        .center {
            position: relative;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        h4 {
            color: black;
            font-family: arial;
            font-weight: 300;
            font-size: 16px;
        }

        .topnav {
            background-color: #333;
            overflow: hidden;
        }

        /* Style the links inside the navigation bar */
        .topnav a {
            float: right;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Change the color of links on hover */
        .topnav a:hover {
            background-color: #e7880d;
            color: black;
        }

        .message {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>


    <title>FreelancerOPS</title>

</head>

<body>
    <div class="topnav">
        <img src="{{ URL::to('images/LogoTA.png') }}" style="padding-top:5px;padding-bottom:5px; padding-left:10px"
                alt="FreelancerOPS Web Logo" width="200px" height="50px">
        <a href="/loginMember" style="padding-top: 20px;padding-bottom:20px">Login</a>
    </div>
    <div class="wrapper">
        {{-- <center>
            <img src="{{ URL::to('images/LogoTA.png') }}" style="padding-top:30px;padding-bottom:20px"
                alt="FreelancerOPS Web Logo" width="350" height="80">
        </center> --}}
        <div
            style=" text-align: left; height:100%;
             background-repeat: no-repeat; background-size: cover;
            background-image: url('{{ URL::to('images/landingFreelancer.jpg') }}')">


            <div
                style="background-color: rgba(0, 0, 0, 0.5); height:100%; align-items: center; justify-content:center;">
                <div class="center" style="padding-left: 20px">
                    <table>
                        <tr>
                            {{-- <td>
                                <img src="https://image.freepik.com/free-vector/freelancer-working-laptop-her-house_1150-35054.jpg"
                                    style="padding:20px 10px 20px 10px;" alt="FreelancerOPS Web Logo" width="300"
                                    height="300">
                            </td> --}}
                            <td>
                                <h1 style="font-weight: bold; color: #e7880d">
                                    Freelancer
                                </h1>
                                <div style="width: 50%">
                                    <p class="message" style="font-weight: bold; font-size:20px; color:white">
                                        Anda memiliki keahlian dan ingin mendapatkan pekerjaan? Mendaftarlah sebagai
                                        Freelancer untuk menemukan proyek yang anda inginkan. Dengan menjadi freelancer
                                        anda akan dapat mencari dan mengambil berbagai proyek yang tersedia pada web.
                                        Mari bergabung dan mulai dapatkan proyek anda.
                                    </p>
                                    <a href="/register" class="message" style="font-weight: bold; font-size:20px; color:white">Daftar Sekarang</a>
                                </div>

                            </td>
                        </tr>
                    </table>
                </div>

            </div>


        </div>
        <div
            style="align-items: right;justify-content:end; text-align: right; height:100%;  background-repeat: no-repeat; background-size: cover;
        background-image: url('{{ URL::to('images/landingClient.png') }}')">

            <div style="background-color: rgba(0, 0, 0, 0.5); height:100%;">
                <div class="center" style="padding-right: 20px">
                    <table>
                        <tr>
                            <td>
                                <h1 style="font-weight: bold; color: #e7880d">
                                    Client
                                </h1>
                                <div style="width: 50%; float:right">
                                    <p class="message"
                                        style="text-align: right; font-size:20px; font-weight: bold; color:white">
                                        Anda memiliki banyak pekerjaan dan membutuhkan bantuan? Mendaftarlan sebagai
                                        Client dan mulai bagikan proyek anda. Dengan mendaftar sebagai client anda dapat membagikan proyek dan
                                        menerima freelancer yang sesuai dengan kebutuhan anda. Mari bergabung dan mulai dapatkan freelancer anda.
                                    </p>
                                    <a href="/register" class="message" style="font-weight: bold; font-size:20px; color:white">Daftar Sekarang</a>
                                </div>

                            </td>
                            {{-- <td>
                                <img src="https://image.freepik.com/free-vector/presentation-client-flat-vector-illustration_82574-4073.jpg"
                                    style="padding:20px 10px 20px 10px;" alt="FreelancerOPS Web Logo" width="300"
                                    height="300">
                            </td> --}}
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
