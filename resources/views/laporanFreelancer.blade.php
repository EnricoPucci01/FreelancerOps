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
                                                {{$item['nama']}}
                                            </td>
                                            <td>
                                                {{$item['jumlah']}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="card mt-3">
                                <h3 class="card-title">{{$judul2}}</h3>
                                <div class="card-body" >
                                    <div id="app">
                                        {!! $chart2->container() !!}
                                    </div>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                                    {!! $chart2->script() !!}
                                </div>
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
                                                {{$item['nama']}}
                                            </td>
                                            <td>
                                                {{$item['jumlah']}}
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
</div>




@endsection
