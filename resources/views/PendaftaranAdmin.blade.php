<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="<?php echo asset('cssStyle.css')?>" type="text/css">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <center>
    <p class="mainLogo">
        <a href="home">
            <img src="{{ URL::to('images/LogoTA.png') }}" alt="FreelancerOPS Web Logo" width="350" height="80">
         </a>
     </p>
    </center>
</head>
  <body class="bodyLogin">
      @include('alert')
    <center>
        <div class="card" style="width: 50rem;" >
            <div class="card-body">
              <h3 class="card-title">Register</h5>
            <h6 class="card-subtitle mb-2 text-muted">Please enter your profile</h6>
              <form action={{url("/submitregisterAdmin")}} method="POST">
                @csrf
                  <table>
                      <!-- Nama -->
                      <tr>
                        <td>
                            <label for="name_register" class="form-label">Nama</label>
                        </td>
                      </tr>
                      <tr>
                          <td>
                            <input type="text" name="name_register" class="form-control mb-2">
                          </td>
                      </tr>
                        <!-- Email -->
                      <tr>
                          <td>
                                <label for="email_register" class="form-label">E-mail</label>
                          </td>
                      </tr>
                      <tr>
                          <td>
                            <input type="email" name="email_register" class="form-control mb-2">
                          </td>
                      </tr>
                      <!-- password -->
                      <tr>
                        <td>
                              <label for="pass_register" class="form-label">Password</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                          <input type="password" name="pass_register" class="form-control mb-2">
                        </td>
                    </tr>


                    <!-- Role -->
                    <tr>
                        <td>
                            <label for="role_register" class="form-label">Saya ingin mendaftar sebagai</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select class="selectpicker form-control mb-2" id='role' name="role_register" data-style="btn-default btn-lg">
                                <option value="admin" Selected>Admin</option>
                            </select>
                        </td>
                    </tr>


                    <!-- Nomor HP -->
                    <tr>
                        <td>
                              <label for="phone_register" class="form-label">Nomor HP</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                          <input type="number" name="phone_register" class="form-control mb-2">
                        </td>
                    </tr>

                    <!-- tempat lahir -->
                    <tr>
                        <td>
                              <label for="birthplace_register" class="form-label">Tempat lahir</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                          <input type="text" name="birthplace_register" class="form-control mb-2">
                        </td>
                    </tr>

                     <!-- tanggal lahir -->
                     <tr>
                        <td>
                              <label for="birthdate_register" class="form-label">Tanggal lahir</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                          <input type="date" name="birthdate_register" class="form-control mb-2">
                        </td>
                    </tr>
                  </table>
                  <button type="submit" class="btn btn-primary" style="margin-top: 10px">Daftar</button>
                  <a href="{{url('/')}}" class="btn btn-danger" style="margin-top: 10px">Kembali</a>
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
