@extends('header')
@section('content')
<center>
    <div class="card" style="width: 70%; margin-top: 20px;" >
        <div class="card-body">
          <h3 class="card-title">Tambah Tag</h5>
          <form action={{url("/tambahTag")}} method="POST">
            @csrf
            @method('POST')
              <table>
                        <!-- No Rek Tujuan -->
                        <tr>
                            <td>
                                <label for="no_rek" class="form-label">Nama Tag</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="tag" class="form-control">
                            </td>
                        </tr>

              </table>

              <button type="submit" class="btn btn-primary" style="margin-top: 10px" id='myBtn' >Tambah</button>
                {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
          </form>
        </div>
      </div>
</center>
@endsection
