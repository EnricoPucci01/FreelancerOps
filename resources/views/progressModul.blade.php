@extends('header')
@section('content')
    <center>
        <div class="card mt-3 mb-3" style="width: 90%">
            <h5 class="card-header">Progress {{ $dataModul['title'] }}</h5>
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="fw-bold">
                        <td>
                            Tanggal
                        </td>
                        <td>
                            Progress
                        </td>
                        <td>
                            Status
                        </td>
                        <td>
                            File
                        </td>
                        <td>
                            Laporkan Error
                        </td>
                    </thead>
                    @foreach ($dataProgress as $progress)
                        <tr>
                            <td>
                                {{ $progress['upload_time'] }}
                            </td>
                            <td>
                                {{ $progress['progress'] }}
                            </td>
                            <td>
                                {{ $progress['status'] }}
                            </td>
                            <td>
                                @if ($progress['status'] == 'finish')
                                    <a class='btn btn-primary' href={{ asset("/storage/progress/$progress[file_dir]") }}><i
                                            class="bi bi-box-arrow-down"></i></a>
                                @else
                                    <a class='btn btn-primary' href={{ asset("/storage/progress/$progress[file_dir]") }}><i
                                            class="bi bi-box-arrow-down"></i></a>
                                @endif

                            </td>
                            <td>
                                <a style="height: 31px;" class='btn btn-warning btn-sm'
                                    href={{ url("/errorReport/$progress[modul_id]/$progress[progress_id]") }}>
                                    <h5><i class="bi bi-exclamation-circle fa-2x"></i></h5>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                @if (count($dataProgress) <= 0)
                    <a class='btn btn-primary' href={{ url("/batalFreelancer/$modulTakenId") }}>Batalkan Freelancer</a>
                @else
                    <button class="btn btn-info btn-sm" data-bs-target={{ '#modalTutup' }} data-bs-toggle="modal">Permohonan
                        Pembatalan
                        Freelancer</button>

                    <div class="modal fade" tabindex="-1" id={{ 'modalTutup' }} aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel2">
                                        Batalkan Freelancer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action={{ url("/permohonanPembatalanFreelancer/$modulTakenId") }}>
                                        @csrf
                                        @method('POST')
                                        <label for="alasan">Alasan Membatalkan Freelancer</label>
                                        <br>
                                        <textarea class="form-control" type="textarea" id='alasan'name='alasan' maxlength="500"></textarea>
                                        <div class="modal-footer">
                                            <button class='btn btn-info btn-sm' type="submit">Ya</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endif

                {{-- <a class='btn btn-secondary' href={{url("/loadDetailProyekClient/$dataProyek[proyek_id]/c/")}}>Kembali</a> --}}
                <a class='btn btn-secondary' href={{ url()->previous() }}>Kembali</a>
            </div>
        </div>
    </center>
@endsection
