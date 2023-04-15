@extends('header')
@section('content')
    <div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
        <div class="card mt-3 mb-3">
            <h5 class="card-header">Request Penarikan Dana</h5>

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
                                {{ Carbon\Carbon::parse($penarikan['tanggal_request'])->format('d-m-Y H:i:s') }}
                            </td>
                            <td>
                                @foreach ($dataCust as $cust)
                                    @if ($cust['cust_id'] == $penarikan['cust_id'])
                                        {{ $cust['nama'] }}
                                    @endif
                                @endforeach

                            </td>
                            <td>
                                <p class="text-primary fw-bold">{{ $penarikan['bank'] }}</p>

                            </td>
                            <td>
                                <p class="fw-bold">@money($penarikan['jumlah'], 'IDR', true)</p>
                            </td>
                            <td>
                                @foreach ($dataCust as $cust)
                                    @if ($cust['cust_id'] == $penarikan['cust_id'])
                                        <button class="btn btn-success" type="button" data-bs-target={{"#modalPost$penarikan[penarikan_id]"}}
                                            data-bs-toggle="modal">Setuju</button>

                                        {{-- Modal Post --}}
                                        <div class="modal fade" tabindex="-1"aria-hidden="true" id={{"modalPost$penarikan[penarikan_id]"}}>
                                            <form action={{ url("/createDisb/$cust[nama]/$penarikan[penarikan_id]") }}
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalToggleLabel2">Bukti Transfer</h5>
                                                            <button type="button" class="btn-close close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Masukan bukti transfer dana<br>
                                                            <input type="file" class="form-control" name='buktiTF'>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>
        </div>
    </div>
@endsection
