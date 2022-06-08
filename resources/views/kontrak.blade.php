<!DOCTYPE html>
<html>
<head>
    <title>Kontrak Kerja FreelancerOPS</title>
</head>
<body>
    <h1>Kontrak Kerja FreelancerOPS</h1>

    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>


    <div style="margin-top: 50px">
        Telah mengetahui dan menyetujui:
        <p>{{ $date }}</p>
        <table>
            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: center">Freelancer</td>
                            </tr>
                            <tr>
                                <td>
                                    <div >
                                        <img src="{{base_path()}}/public/storage/sign/{{$freelancer}}.png" style="display:block; object-fit: cover;" width="300" height="100"/>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">{{$freelancer}}</td>
                            </tr>

                        </tbody>
                    </table>
                </td>

                <td>
                    <table>
                        <tr>
                            <td style="text-align: center">Client</td>
                        </tr>
                        <tr>
                            <td>
                                <div >
                                    <img src="{{base_path()}}/public/storage/sign/{{$sign}}.png"  style="display:block; object-fit: cover;" width="300" height="100"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">{{$sign}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>


</body>
</html>
