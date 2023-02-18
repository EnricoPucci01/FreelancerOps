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
    <center>
    <div class="card" style="width: 50rem;" >
            <div class="card-body">
              <h3 class="card-title">What is your skill?</h5>
            <h6 class="card-subtitle mb-2 text-muted">Please pick your skill</h6>
              <form action="">
                  <table>
                      <tr>
                          <td>
                            <select class="selectpicker form-control" data-width="500px" data-style="btn-default btn-lg" multiple data-live-search="true">
                                <option>PHP</option>
                                <option>HTML</option>
                                <option>Javascript</option>
                              </select>
                          </td>
                      </tr>
                  </table>
                  <h3 class="card-title" style="margin-top: 10px">About your self?</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Please describe yourself</h6>
                  <table>
                    <tr>
                        <td>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="width:500px; height: 100px"></textarea>
                                <label for="floatingTextarea2">Describe yourself with max 250 words</label>
                              </div>
                        </td>
                    </tr>
                  </table>
                  <button type="submit" class="btn btn-primary" style="margin-top: 10px">Next</button>
              </form>
            </div>
          </div>
    </center>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
