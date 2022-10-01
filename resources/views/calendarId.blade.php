@extends('header')
@section('content')
<center>
    <div class="card" style="width: 70%; margin-top: 20px;" >
        <div class="card-body">
          <h3 class="card-title">Tambahkan Calendar ID</h5>
          <form action={{url("/updateCalendarId")}} method="POST">
            @csrf
            @method('POST')
              <table>
                  <!-- Nama -->
                    <tr>
                        <td>
                            <div style="width: 300px; overflow-wrap:break-word; text-align: center;" class="text-muted fw-bold">Tambahkan Calender Id anda agar dapat menggunakan fitur Kalender</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="cal_id" class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <button type="button" class=" form-control btn btn-success mt-3" data-toggle="modal" data-target="#modalPost">Tambahkan</button>
                        </td>
                    </tr>


                    {{---------------------------------------------------MODAL-------------------------------------------------}}
                        {{-- Modal Post --}}
                        <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPost">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel2">Ubah Calendar Id?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Anda akan mengubah Calendar Id. Apakah Calendar Id sudah sesuai dengan keinginan anda?<br>

                                    Tekan <b>Ya</b> untuk melanjutkan proses ubah Calendar Id.
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
