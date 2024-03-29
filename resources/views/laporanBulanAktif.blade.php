@extends('header')
@section('content')
    <div style="padding: 15px">
        <div class="card mt-3" style="padding: 20px">
            <div class="card-title">
                <h1>
                    Rekap
                </h1>
            </div>
            <table>
                <tr>
                    <td style="width: 50%;vertical-align: top">
                        <div class="border-right border-left">
                            <table class="table table-striped">
                                <thead class="fw-bold">
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
                                                <a
                                                    href={{ url("/listProyekBulan/$dataRekap[months]") }}>{{ $dataRekap['months'] }}</a>
                                            </td>
                                            <td style="text-align: center">
                                                {{ $dataRekap['counts'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td style="width: 50%; vertical-align: top">
                        <div class="border-right border-left">
                            <table class="table table-striped">
                                <thead class="fw-bold">
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
                                                {{ $dataRekapKat['judul'] }}
                                            </td>
                                            <td style="text-align: center">
                                                {{ $dataRekapKat['counts'] }}
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
                    Laporan Jumlah Proyek Di Ambil Untuk Kategori {{ $judul }}
                </h1>
            </div>
            <div class="card-body">
                <form action={{ url('/loadLaporanBulanAktif') }} method="GET">
                    @csrf
                    @method('GET')
                    <table>
                        <tr>
                            <td>
                                <select class="custom-select" name='ddKategori'>
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Pilih Kategori
                                    </button>
                                    <div class="dropdown-menu"aria-labelledby="dropdownMenuButton">
                                        @foreach ($itemKategori as $item)
                                            <option value={{ $item->kategorijob_id }}
                                                {{ $selected == $item->kategorijob_id ? 'Selected' : '' }}>
                                                {{ $item->judul_kategori }}</option>
                                        @endforeach
                                    </div>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-lg">
                                    <i class="bi bi-search"></i>
                                </button>
                            </td>
                        </tr>
                    </table>

                </form>
                <canvas id="myChart" height="100px"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script type="text/javascript">
                    var labels = @json($labels);
                    var users = @json($data);

                    const data = {
                        labels: labels,
                        datasets: [{
                            label: "Jumlah",
                            backgroundColor: 'rgb(255, 99, 132)',
                            data: users,
                        }]
                    };

                    const config = {
                        type: 'bar',
                        data: data,
                        options: {}
                    };

                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );
                </script>
            </div>
        </div>
    </div>
@endsection
