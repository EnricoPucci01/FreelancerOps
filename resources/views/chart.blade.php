@extends('header')
@section('content')
    <center>
        <table class="mt-3 mb-3">
            <tr>
                @if ($chart != '0')
                    <td >
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
                    <td  style="width: 500px;">
                        <div class="card" style="height:700px; padding:10px">
                            <h3 class="card-title">{{ $chart1->options['chart_title'] }}</h3>
                            @if ($statusChart1 != "0")
                            <table class="table table-sm table-striped border-right border-left">
                                @foreach ($statusChart1 as $item)
                                <tr>
                                    <td>
                                        {{$item['statusPay']}}
                                    </td>
                                    <td>
                                        {{$item['jumlah']}}
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
