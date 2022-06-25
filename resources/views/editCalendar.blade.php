@extends('header')
@section('content')
<center>
    <div class="card" style="width: 70%; margin-top: 20px;" >
        <div class="card-body">
          <h3 class="card-title">Edit Kalender</h5>
          <form action={{url("/insertEvent")}} method="POST">
            @csrf
            @method('POST')
              <table>
                  <!-- Nama -->
                    <tr>
                        <td>
                            <label for="name_project" class="form-label">Nama</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="name_Event" class="form-control">
                        </td>
                    </tr>


                    <!-- Start -->
                    <tr>
                        <td>
                            <label for="deadline" class="form-label">Dimulai Pada </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="date" name="start_Event" value={{Carbon\Carbon::now()->format('Y-m-d')}} class="form-control">
                        </td>
                        <td>
                            <input type="time" name="timeStart_Event" value={{Carbon\Carbon::now()->format('H:i')}} class="form-control ml-2">
                        </td>
                    </tr>

                    <!-- End -->
                    <tr>
                        <td>
                            <label for="deadline" class="form-label">Berakhir Pada </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="date" name="end_Event" value={{Carbon\Carbon::now()->addDay(1)->format('Y-m-d')}} class="form-control">
                        </td>

                        <td>
                            <input type="time" name="timeEnd_Event" value={{Carbon\Carbon::now()->format('H:i')}} class="form-control ml-2">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <button type="button" class=" form-control btn btn-success mt-3" data-toggle="modal" data-target="#modalPost">Masukan</button>
                        </td>
                    </tr>


                    {{---------------------------------------------------MODAL-------------------------------------------------}}
                        {{-- Modal Post --}}
                        <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPost">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel2">Tambahkan Acara?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Anda akan mendaftarkan acara baru. Apakah acara sudah sesuai dengan keinginan anda?<br>

                                    Tekan <b>Ya</b> untuk melanjutkan proses pendaftaran event.
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Ya</button>
                                </div>
                            </div>
                        </div>
                    {{-------------------------------------------------------------------------------------------------------}}

                {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}
            </table>
        </form>
        </div>
      </div>
</center>
@endsection
