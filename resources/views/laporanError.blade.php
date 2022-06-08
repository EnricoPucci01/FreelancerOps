@extends('header')
@section('content')

<center>

        <div class="mt-3 ml-3 mr-3">
            <form action={{url("/errorSubmit/$listModul[modul_id]")}} method="post" enctype="multipart/form-data">
                @method('POST')
                @csrf
            <h2 class="mb-3">
                Laporan Error {{$listModul['title']}}
            </h2>
            <table class="table table-stipped">
                <thead class="fw-bold">
                    <td>
                        Tanggal Error
                    </td>
                    <td>
                        Halaman Error
                    </td>
                    <td>
                        Aksi
                    </td>
                    <td>
                        Deskripsi
                    </td>
                    <td>
                        File
                    </td>
                    <td style="width: 4%">

                    </td>
                </thead>


                @foreach ($listError as $error)
                    <tr>
                        <td>
                            {{$error['report_time']}}
                        </td>
                        <td>
                            {{$error['halaman_error']}}
                        </td>
                        <td>
                            {{$error['aksi']}}
                        </td>
                        <td>
                            {{$error['report_desc']}}
                        </td>
                        <td>
                            <a href={{url("/downloadFileError/$error[report_data]")}} class="btn btn-primary"><i class="bi bi-box-arrow-down"></i></a>
                        </td>
                        <td>
                            @if (is_null($error['status']) )
                                <input class="form-check-input mx-auto" type="checkbox" value={{$error['report_id']}} name="checkError[]" id="flexCheckDefault">
                            @else
                                <p class="fw-bold text-success"><i class="bi bi-check-circle"></i></p>
                            @endif

                        </td>
                    </tr>

                @endforeach

            </table>

            <tr>
                <button type="button" class="btn btn-primary" style="margin-top: 10px" data-bs-target="#modalPost" data-bs-toggle="modal" class="btn btn-success btn-sm">Selesaikan</button>
            </tr>
        </div>
                    {{---------------------------------------------------MODAL-------------------------------------------------}}
                        {{-- Modal Post --}}
                        <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPost">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel2">Selesaikan Error?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Masukan File Yang Sudah Selesai</h6>
                                    <input type="file" class="form-control" name="fileSelesai">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Ya</button>
                                </div>
                            </div>
            </form>
    </div>

                    {{-------------------------------------------------------------------------------------------------------}}
</center>
@endsection
