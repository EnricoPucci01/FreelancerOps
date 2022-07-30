@extends('header')
@section('content')
    <style type="text/css">
        .pagination {
            justify-content: center;
        }
    </style>
    <center>
        <div class='card mt-3' style="padding: 20px">
            <div class="card-title"> <h1>
                Daftar Proyek Diambil Pada Bulan {{$month}}
            </h1></div>
            @if (count($listproyek) > 0)
                <table class="table table-stiped">
                    <thead class="fw-bold">
                        <tr>
                            <td>
                                Modul
                            </td>
                            <td>
                                Freelancer
                            </td>
                            <td>
                                Tanggal
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listproyek as $item)
                            <tr>
                                <td>
                                    {{ $item->title }}
                                </td>
                                <td>
                                    {{ $item->nama }}
                                </td>
                                <td>
                                    {{ $item->tanggal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center; margin-top: 15%;">
                    <h1>Anda Tidak Memiliki Proyek ...</h1>
                </div>
        </div>
        @endif
        {{ $listproyek->links('pagination::bootstrap-4') }}
    </center>

@endsection
