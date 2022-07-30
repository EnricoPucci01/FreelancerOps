@extends('header')
@section('content')
<div class="card mt-3" style="padding: 20px">
    <div class="card-title">
        <h1>
            Rekap
        </h1>
    </div>
<table>
    <tr>
        <td style="width: 50%">
            <div class="border-right border-left border-bottom">
                <table class="table table-striped" >
                    <thead  class="fw-bold">
                        <tr>
                            <td style="text-align: center">
                                Bulan
                            </td>
                            <td style="text-align: center">
                                Jumlah Proyek Diambil
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekap as $dataRekap)
                            <tr>
                                <td style="text-align: center">
                                    <a href={{url("/listProyekBulan/$dataRekap[months]")}}>{{$dataRekap['months']}}</a>
                                </td>
                                <td style="text-align: center">
                                    {{$dataRekap['counts']}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </td>
        <td style="width: 50%">
            <div class="border-right border-left border-bottom " >
                <table class="table table-striped" >
                    <thead  class="fw-bold">
                        <tr>
                            <td style="text-align: center">
                                Nama Kategori
                            </td>
                            <td style="text-align: center">
                                Jumlah Proyek Diambil
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekapKategori as $dataRekapKat)
                            <tr>
                                <td style="text-align: center">
                                    {{$dataRekapKat['judul']}}
                                </td>
                                <td style="text-align: center">
                                    {{$dataRekapKat['counts']}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
</table>
</div>

    <div class="card mt-3" style="padding: 20px">
        <div class="card-title">
            <h1>
                Laporan Bulan Aktif
            </h1>
        </div>
        <table class="table table-striped">
            <thead class="fw-bold">
                <tr>
                    <td style="text-align: center">
                        Tanggal
                    </td>
                    @foreach ($kategoriJob as $itemJob)
                        <td style="text-align: center; font-size: 15px">
                            {{ $itemJob->judul_kategori }}
                        </td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dataBulan as $itemBulan)
                    <tr>
                        <td style="text-align: center ;width:10%">
                            {{ $itemBulan['tanggal'] }}
                        </td>
                        @foreach ($kategoriJob as $itemJob)
                            @php
                                $filled = false;
                            @endphp
                            <td style="text-align:center">
                                @foreach ($itemBulan['data'] as $data)
                                    @if ($itemJob->judul_kategori == $data['judul_kategori'])
                                        {{ $data['counts'] }}
                                        @php
                                            $filled = true;
                                        @endphp
                                    @endif
                                @endforeach

                                @php
                                    if (!$filled) {
                                        echo '0';
                                    }
                                @endphp
                            </td>
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
