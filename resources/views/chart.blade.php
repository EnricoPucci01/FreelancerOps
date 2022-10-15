@extends('header')
@section('content')
    <center>
        <table class="mt-3 mb-3">
            <tr>
                @if ($chart != '0')
                    <td style="width: 30%">
                        <div class="card">
                            <h3 class="card-title">{{ $judul }}</h3>
                            <div class="card-body">
                                <div id="app">
                                    {!! $chart->container() !!}
                                </div>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                                {!! $chart->script() !!}
                            </div>
                        </div>
                    </td>
                @endif


                @if ($chart1 != '0')
                    <td style="width: 500px;">
                        <div class="card">
                            <div class="row justify-content-center">
                                <div class="card-body">

                                    <h1>{{ $chart1->options['chart_title'] }}</h1>
                                    {!! $chart1->renderHtml() !!}

                                </div>
                            </div>
                        </div>
                        {!! $chart1->renderChartJsLibrary() !!}
                        {!! $chart1->renderJs() !!}
                    </td>
                @endif


                @if ($chart2 != '0')
                    <td style="width: 700px;">
                        <div class="card">
                            <h3 class="card-title">{{ $judul2 }}</h3>
                            <div class="card-body">
                                <div id="app">
                                    {!! $chart2->container() !!}
                                </div>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                                {!! $chart2->script() !!}
                            </div>
                        </div>
                    </td>
                @endif

            </tr>
        </table>






    </center>
@endsection
