@extends('header')
@section('content')
<center>
    <div class="card mt-3 mb-3" style="width: 90%">
        <h5 class="card-header">Progress {{$dataModul['title']}}</h5>
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
                </thead>
                @foreach ($dataProgress as $progress)
                    <tr>
                        <td>
                            {{$progress['upload_time']}}
                        </td>
                        <td>
                            {{$progress['progress']}}
                        </td>
                        <td>
                            {{$progress['status']}}
                        </td>
                        <td>
                            @if ($progress['status']=='finish')
                                <a class='btn btn-primary' href={{url("/downloadProgress/finish/$progress[file_dir]")}}><i class="bi bi-box-arrow-down"></i></a>
                            @else
                                <a class='btn btn-primary' href={{url("/downloadProgress/progress/$progress[file_dir]")}}><i class="bi bi-box-arrow-down"></i></a>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </table>
            <a class='btn btn-primary' href={{url("/batalFreelancer/$modulTakenId")}}>Batalkan Freelancer</a>
            <a class='btn btn-danger' href={{url("/errorReport/$dataModul[modul_id]")}}>Laporkan Error</a>
            <a class='btn btn-secondary' href={{url("/loadDetailProyekClient/$dataProyek[proyek_id]/c/".session()->get('cust_id'))}}>Kembali</a>
        </div>
    </div>
</center>
@endsection
