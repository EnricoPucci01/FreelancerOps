@extends('header')

@section('content')
    <center>
        <div class="card" style="width: 70%; margin-top: 20px;">
            <div class="card-body">
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: center">
                            <h3 class="card-title">Tambah Acara</h3>
                        </td>
                        <td>
                            <a href={{ url('/tutorialCalendar/GServiceAcc/1') }}><i style="font-size: 1.73em;"
                                    class="bi bi-question-circle text-dark"></i></a>
                        </td>
                    </tr>
                    <tr style="text-align: center">
                        <td style="text-align: center">
                            @if (!$calendarId)
                                <div style="text-align: center;width:100%;">
                                    <p style="text-align: center" class="text-danger">Anda belum menambahkan Calendar ID, Untuk menambahkan Calendar ID <br>anda dapat mengikuti panduan dengan menekan <i style="font-size: 0.73em;"
                                        class="bi bi-question-circle text-dark"></i></p>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>

                <form action={{ url('/insertEvent') }} method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" value='noinsert' name='warningCal'>
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
                                <input type="date" name="start_Event" value={{ Carbon\Carbon::now()->format('Y-m-d') }}
                                    class="form-control">
                            </td>
                            <td>
                                <input type="time" name="timeStart_Event" value={{ Carbon\Carbon::now()->format('H:i') }}
                                    class="form-control ml-2">
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
                                <input type="date" name="end_Event"
                                    value={{ Carbon\Carbon::now()->addDay(1)->format('Y-m-d') }} class="form-control">
                            </td>

                            <td>
                                <input type="time" name="timeEnd_Event" value={{ Carbon\Carbon::now()->format('H:i') }}
                                    class="form-control ml-2">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                @if (!$calendarId)
                                    <button type="submit" class="form-control btn btn-secondary mt-3"
                                        disabled>Masukan</button>
                                @else
                                    <button type="submit" class="form-control btn btn-success mt-3">Masukan</button>
                                @endif

                            </td>
                            <td>
                                <a type="button" href={{ '/calendarId' }} class="form-control btn btn-primary mt-3">Ubah
                                    Calendar ID</a>
                            </td>
                        </tr>

                    </table>
                </form>
                {{-- -------------------------------------------------MODAL----------------------------------------------- --}}
                {{-- Modal Post --}}
                @if (session()->has('errorCal'))
                    <script>
                        window.onload = function() {
                            let myModal = new bootstrap.Modal(document.getElementById('modalCal'), {});
                            myModal.show();
                        };
                    </script>
                    <div class="modal" tabindex="-1" role="dialog" id="modalCal">
                        <form action={{ url('/insertEvent') }} method="POST">
                            @csrf
                            @method('POST')
                            <input type="hidden" value="insert" name='warningCal'>
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel2">Peringatan</h5>
                                        <button type="button" class="btn-close close" data-dismiss="modal"
                                            aria-label="Close">
                                            @if (session()->has('errorCal'))
                                                {{session()->forget('errorCal')}}
                                            @endif
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Anda telah memiliki acara pada hari tersebut, anda yakin tetap ingi menambahkan
                                        acara?<br>

                                        Tekan <b>Ya</b> untuk melanjutkan proses penambahan acara.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Ya</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
                {{-- --------------------------------------------------------------------------------------------------- --}}

                {{-- <button type="button" class="btn btn-primary" id='tambah' style="margin-top: 10px">tambah</button> --}}


            </div>
        </div>
    </center>
@endsection
