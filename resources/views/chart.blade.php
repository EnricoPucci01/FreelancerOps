@extends('header')
@section('content')
    <center>
        <table class="mt-3 mb-3">
            <tr>
                @if ($chart != '0')
                    <td style="width: 600px;">
                        <div class="card" style="height:700px;padding:10px">
                            <h3 class="card-title">{{ $judul }}</h3>
                            <div class="card-body">
                                <div id="app">
                                    <canvas id="myChart0"></canvas>
                                </div>
                            </div>
                        </div>
                    </td>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script type="text/javascript">
                        var labels0 = @json($labels0);
                        var users0 = @json($data0);
                        var type0 = @json($type0);
                        var datasets0;
                        if (type0 == 'line') {
                            datasets0 = [{
                                label: "Jumlah",
                                borderColor: ['rgb(255, 99, 132)', 'rgb(255,140,0)', 'rgb(0,128,0)', 'rgb(30,144,255)',
                                    'rgb(220,20,60)', 'rgb(46,139,87)', 'rgb(219,112,147)', 'rgb(148,0,211)',
                                    'rgb(128,128,128)', 'rgb(255,222,173)', 'rgb(222,184,135)'
                                ],
                                tension: 0.1,
                                fill: false,
                                data: users0
                            }];
                        } else if(type0 == 'pie'){
                            datasets0 = [{
                                label: "Jumlah",
                                color: ['rgb(255, 99, 132)', 'rgb(255,140,0)', 'rgb(0,128,0)', 'rgb(30,144,255)',
                                    'rgb(220,20,60)', 'rgb(46,139,87)', 'rgb(219,112,147)', 'rgb(148,0,211)',
                                    'rgb(128,128,128)', 'rgb(255,222,173)', 'rgb(222,184,135)'
                                ],
                                data: users0
                            }]
                        }else {
                            datasets0 = [{
                                label: "Jumlah",
                                borderColor: ['rgb(255, 99, 132)', 'rgb(255,140,0)', 'rgb(0,128,0)', 'rgb(30,144,255)',
                                    'rgb(220,20,60)', 'rgb(46,139,87)', 'rgb(219,112,147)', 'rgb(148,0,211)',
                                    'rgb(128,128,128)', 'rgb(255,222,173)', 'rgb(222,184,135)'
                                ],
                                data: users0
                            }];
                        }
                        const data0 = {
                            labels: labels0,
                            datasets: datasets0
                        };

                        const config0 = {
                            type: type0,
                            data: data0,
                            options: {

                            }
                        };

                        const myChart0 = new Chart(
                            document.getElementById('myChart0'),
                            config0
                        );
                    </script>
                @endif


                @if ($chart1 != '0')
                    <td style="width: 500px;">
                        <div class="card" style="height:700px; padding:10px">
                            <h3 class="card-title">{{ $chart1->options['chart_title'] }}</h3>
                            @if ($statusChart1 != '0')
                                <table class="table table-sm table-striped border-right border-left">
                                    @foreach ($statusChart1 as $item)
                                        <tr>
                                            <td>
                                                {{ $item['statusPay'] }}
                                            </td>
                                            <td>
                                                {{ $item['jumlah'] }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            @endif
                            <div class="card-body">
                                {!! $chart1->renderHtml() !!}

                            </div>
                        </div>
                        {!! $chart1->renderChartJsLibrary() !!}
                        {!! $chart1->renderJs() !!}
                    </td>
                @endif


                @if ($chart2 != '0')
                    <td style="width: 600px;">
                        <div class="card" style="height:700px;padding:10px">
                            <h3 class="card-title">{{ $judul2 }}</h3>
                            <div class="card-body">
                                <div id="app">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </td>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script type="text/javascript">
                        var labels = @json($labels);
                        var users = @json($data);
                        var type = @json($type);
                        var datasets;
                        if (type == 'line') {
                            datasets = [{
                                label: "Jumlah",
                                borderColor: ['rgb(255, 99, 132)', 'rgb(255,140,0)', 'rgb(0,128,0)', 'rgb(30,144,255)',
                                    'rgb(220,20,60)', 'rgb(46,139,87)', 'rgb(219,112,147)', 'rgb(148,0,211)',
                                    'rgb(128,128,128)', 'rgb(255,222,173)', 'rgb(222,184,135)'
                                ],
                                tension: 0.1,
                                fill: false,
                                data: users
                            }];
                        } else if(type == 'pie'){
                            datasets = [{
                                label: "Jumlah",
                                color: ['rgb(255, 99, 132)', 'rgb(255,140,0)', 'rgb(0,128,0)', 'rgb(30,144,255)',
                                    'rgb(220,20,60)', 'rgb(46,139,87)', 'rgb(219,112,147)', 'rgb(148,0,211)',
                                    'rgb(128,128,128)', 'rgb(255,222,173)', 'rgb(222,184,135)'
                                ],
                                data: users
                            }]
                        }else {
                            datasets = [{
                                label: "Jumlah",
                                borderColor: ['rgb(255, 99, 132)', 'rgb(255,140,0)', 'rgb(0,128,0)', 'rgb(30,144,255)',
                                    'rgb(220,20,60)', 'rgb(46,139,87)', 'rgb(219,112,147)', 'rgb(148,0,211)',
                                    'rgb(128,128,128)', 'rgb(255,222,173)', 'rgb(222,184,135)'
                                ],
                                data: users
                            }];
                        }
                        const data = {
                            labels: labels,
                            datasets: datasets
                        };

                        const config = {
                            type: type,
                            data: data,
                            options: {

                            }
                        };

                        const myChart = new Chart(
                            document.getElementById('myChart'),
                            config
                        );
                    </script>
                @endif

            </tr>
        </table>
    </center>
@endsection
