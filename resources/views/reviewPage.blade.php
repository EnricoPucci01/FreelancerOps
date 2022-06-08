@extends('header')
@section('content')

    <div style="margin: 20px 20px 20px 20px">
        <form action="{{url("/submitReview/$freelancer[cust_id]/$client/$modul[modul_id]")}}" method="post" enctype="multipart/form-data">
            @method('POST')
            @csrf
            <div class="card mt-3 center" style="width:50%">
                <div class="card-header">
                  <h1 class="fw-bold">{{$freelancer['nama']}}</h1>
                </div>

                <div class="card-body">
                    <table style="width: 100%">

                      <tr>
                        <td>
                          <h5 class="card-title">Review Untuk {{$modul['title']}}</h5>
                        </td>

                      </tr>

                      <tr>
                        <td>
                          <p class="form-label mt-3" >Apakah Kinerja Freelancer {{$freelancer['nama']}} Memuaskan?</p>
                        </td>

                      </tr>

                      <tr>
                        <td>
                          <div class="rate" >
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" name="rate" value="1" checked/>
                            <label for="star1" title="text">1 star</label>
                          </div>
                        </td>

                      </tr>

                      <tr>
                        <td>
                          <p class="form-label mt-3" style="margin-top:50px">Pengalaman Anda Bekerja Dengan Freelancer Ini</p>
                        </td>

                      </tr>

                      <tr>
                        <td>
                          <textarea class="form-control"  aria-label="Deskripsikan dalam 1200 huruf" name="revDesc" maxlength="1200" id='rev_desc'></textarea>
                        </td>

                      </tr>
                    </table>


                    <center>
                        <button type="submit" class="btn btn-success mt-3">Submit</button>
                        <a href={{url("/loadDetailProyekClient/$proyekId/c/".session()->get('cust_id'))}} class="btn btn-warning mt-3">Kembali</a>
                    </center>
                </div>
              </div>


        </form>
    </div>

<style type="text/css">
.center {
  margin-left: auto;
  margin-right: auto;
}

 *{
        margin: 0;
        padding: 0;
    }
    /* table, tr, td {
      border: 1px solid;
    } */
    .rate {
        float: left;
        height: 46px;
        padding: 0 10px;

    }
    .rate:not(:checked) > input {
        position:absolute;
        top:-9999px;
    }
    .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
    }
    .rate:not(:checked) > label:before {
        content: '★ ';
    }
    .rate > input:checked ~ label {
        color: #ffc700;
    }
    .rate:not(:checked) > label:hover,
    .rate:not(:checked) > label:hover ~ label {
        color: #deb217;
    }
    .rate > input:checked + label:hover,
    .rate > input:checked + label:hover ~ label,
    .rate > input:checked ~ label:hover,
    .rate > input:checked ~ label:hover ~ label,
    .rate > label:hover ~ input:checked ~ label {
        color: #c59b08;
    }
</style>

@endsection
