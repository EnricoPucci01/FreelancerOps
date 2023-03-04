<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="<?php echo asset('cssStyle.css')?>" type="text/css">
  <head>
    @laravelPWA
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Freelancing Web">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel=“icon” href=”favicon.ico” type=“image/x-icon”>

    <link rel=“shortcut icon” href=“favicon.ico” type=“image/x-icon”>
    <center>
        <p class="mainLogo mt-3">
            <a href="home">
                <img src="{{ URL::to('images/LogoTA.png') }}" alt="FreelancerOPS Web Logo" width="350" height="80">
             </a>
         </p>
    </center>
  </head>
  <body class="bodyLogin">

    @include('alert')
<center>
    <div class="card" style="width: 30rem;" >
        <div class="card-body">
          <h3 class="card-title">Login</h5>
          <form action={{url("/loginops")}} method="GET">
            @csrf
            @method('GET')
              <table>
                  <tr>
                    <td>
                        <label for="email_login" class="form-label">Email</label>
                    </td>
                  </tr>
                  <tr>
                      <td>
                        <input type="email" name="email_login" class="form-control">
                      </td>
                  </tr>
                  <tr>
                      <td>
                            <label for="pass_login" class="form-label mt-3">Password</label>
                      </td>
                  </tr>
                  <tr>
                      <td>
                        <input type="password" name="pass_login" class="form-control">
                      </td>
                  </tr>
              </table>
              <button type="submit" class="btn btn-primary mt-3">Masuk</button>
          </form>
          <p>Belum Memiliki Akun?</p>
          <a href="/register" class="card-link">Daftar Disini</a>
        </div>
      </div>
</center>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
