@extends('header')

@section('content')

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <style tyle="text/css">
         body { text-align: center; padding: 150px; }
        h1 { font-size: 50px; }
        body { font: 20px Helvetica, sans-serif; color: #333; }
        article { display: block; text-align: left; width: 650px; margin: 0 auto; }
        a { color: #dc8100; text-decoration: none; }
        a:hover { color: #333; text-decoration: none; }
    </style>
    <meta content="JavaScript" name="vs_defaultClientScript">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
</head>
<body>
    <article>
        <h1>Oops, Anda sedang offline!</h1>
        <div>
        <p>Koneksi internet anda sedang buruk atau anda sedang tidak terhubung pada internet.</p>
        <p>Silahkan periksa kembali koneksi internet anda atau tunggu sesaat.</p>
        </div>
    </article>
</body>
</html>


@endsection
