<link rel="stylesheet" href="<?php echo asset('cssStyle.css')?>" type="text/css">

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <center>
    <p class="mainLogo">
        <a href="home">
            <img src="{{ URL::to('images/LogoTA.png') }}" width="350" height="80">
         </a>
     </p>
    </center>

</head>
  <body class="bodyLogin">
      @include('alert')
    <center>
        <div class="card" style="width: 50rem;" >
            <div class="card-body">
              <h3 class="card-title">Verifikasi E-Mail</h5>
            <h6 class="card-subtitle mb-2 text-muted">Kode Verifikasi Telah Dikirimkan Ke Email Anda</h6>
              <form action={{url("/verify")}} method="POST">
                @csrf
                @method('POST')
                    <div class="card" style="width: 40%;">
                        <div class="card-body">
                        <h5 class="card-title">Verifikasi Email</h5>
                            <input type="text" name='code' class="form-control">
                        <button type="submit" class="btn btn-primary mt-3">Verifikasi</button>
                        </div>
                    </div>
                  <a href="{{url('/')}}" class="btn btn-secondary" style="margin-top: 10px">Kembali</a>
              </form>
            </div>
          </div>
    </center>
    <script src="<?php echo asset('scriptRegister.js')?>"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
