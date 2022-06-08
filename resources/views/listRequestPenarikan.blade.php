@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
    <div class="card mt-3 mb-3" >
        <h5 class="card-header">Request Penarikan Uang</h5>

        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead class="fw-bold">
                    <tr>
                        <td>
                            Tanggal
                        </td>
                        <td>
                            Pemohon
                        </td>
                        <td>
                            Bank
                        </td>
                        <td>
                            Jumlah
                        </td>
                        <td>

                        </td>
                    </tr>
                </thead>
                @foreach ($dataPenarikan as $penarikan)
                    <tr>
                        <td>
                            {{$penarikan['tanggal_request']}}
                        </td>
                        <td>
                            @foreach ($dataCust as $cust)
                                @if ($cust['cust_id']==$penarikan['cust_id'])
                                    {{$cust['nama']}}
                                @endif
                            @endforeach

                        </td>
                        <td>
                            <p class="text-primary fw-bold">{{$penarikan['bank']}}</p>

                        </td>
                        <td>
                          <p class="fw-bold">@money($penarikan['jumlah'],'IDR',true)</p>
                        </td>
                        <td>
                            @foreach ($dataCust as $cust)
                                @if ($cust['cust_id']==$penarikan['cust_id'])
                                    <a href={{url("/createDisb/$cust[nama]/$penarikan[penarikan_id]")}} class="btn btn-success">Setuju</a>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach

            </table>


            <a class='btn btn-secondary' href={{url("/dashboard")}}>Kembali</a>
        </div>
    </div>
</div>

@endsection
