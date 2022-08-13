@extends('header')
@section('content')
<div class="card mt-3" style="padding: 20px">
    <div class="card-title">
        <h1>
           Laporan Freelancer Aktif
        </h1>
    </div>
    <table class="table table-striped">
        <thead class="fw-bold">
            <tr>
                <td style="text-align: center">
                    Nama
                </td>
                <td style="text-align: center">
                    Jumlah Proyek Selesai
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataFreelancer as $itemFreelancer)
                <tr>
                    <td style="text-align: center ;width:10%">
                        {{ $itemFreelancer['nama'] }}
                    </td>
                    <td style="text-align:center">
                       {{$itemFreelancer['Jumlah']}}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
