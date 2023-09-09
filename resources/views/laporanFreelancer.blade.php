@extends('header')
@section('content')
    <div style="padding: 20px">
        <table style="width:100%; height:100%">
            <tr>
                <td style="width:50%; height:100%">
                    <div style="width:100%; height:100%;top:0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <div class="card">
                            <H3 class="card-title" style="padding: 10px">Spesialisasi Freelancer</H3>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead class="fw-bold">
                                        <tr>
                                            <td>
                                                Spesialisasi
                                            </td>
                                            <td>
                                                Jumlah
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataFreelancer as $item)
                                            <tr>
                                                <td>
                                                    {{ $item['nama'] }}
                                                </td>
                                                <td>
                                                    {{ $item['jumlah'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="card mt-3">
                                    <h3 class="card-title">{{ $judul2 }}</h3>
                                    <canvas id="myChart" height="100px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="width:50%; height:100%">
                    <div style="width:100%; height:100%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
                        <div class="card">
                            <H3 class="card-title" style="padding: 10px">
                                Spesialisasi Kebutuhan Proyek
                            </H3>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead class="fw-bold">
                                        <tr>
                                            <td>
                                                Kategori
                                            </td>
                                            <td>
                                                Jumlah
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataKebutuhan as $item)
                                            <tr>
                                                <td>
                                                    {{ $item['nama'] }}
                                                </td>
                                                <td>
                                                    {{ $item['jumlah'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script type="text/javascript">
            var labels = @json($labels);
            var users = @json($data);

            const data = {
                labels: labels,
                datasets: [{
                    label: "Jumlah",
                    backgroundColor: ['rgb(255, 99, 132)', 'rgb(255,140,0)', 'rgb(0,128,0)', 'rgb(30,144,255)',
                        'rgb(220,20,60)', 'rgb(46,139,87)', 'rgb(219,112,147)', 'rgb(148,0,211)',
                        'rgb(128,128,128)', 'rgb(255,222,173)', 'rgb(222,184,135)'
                    ],
                    data: users,
                }]
            };

            const config = {
                type: 'pie',
                data: data,
                options: {

                }
            };

            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        </script>
    </div>
@endsection
