@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
        <div class='card mt-3'>
            <div class="card-header">
               <h4>Laporan Proyek</h4>
            </div>
            <ul class="nav nav-tabs mt-2">
                <li class="nav-item">
                  <a class="{{$status == 'pengerjaan' ? "nav-link active" : "nav-link"}}" href={{url("/laporanProyekAdmin/pengerjaan")}}>Pengerjaan</a>
                </li>
                <li class="nav-item">
                  <a class="{{$status == 'selesai' ? "nav-link active" : "nav-link"}}" href={{url("/laporanProyekAdmin/selesai")}}>Selesai</a>
                </li>
                <li class="nav-item">
                  <a class="{{$status == 'dibatalkan' ? "nav-link active" : "nav-link"}}" href={{url("/laporanProyekAdmin/dibatalkan")}}>Dibatalkan</a>
                </li>
            </ul>

            <div class="card-body">

                <form method="POST" action={{($status=='selesai')? url("/filterLaporanAdmin/selesai")
                :($status=="dibatalkan")? url("/filterLaporanAdmin/dibatalkan")
                :url("/filterLaporanAdmin/pengerjaan")}}>
                    @csrf
                    @method('POST')

                    <table>
                        <tr>
                            <td>
                                <input type="date" name='dateStart' value={{Carbon\Carbon::now()->subDays(1)}} class="form-control">
                            </td>
                            <td>
                                 -
                            </td>
                            <td>
                                <input type="date" name='dateEnd' value={{Carbon\Carbon::now()}} class="form-control">
                            </td>
                            <td>
                                <button class="btn btn-warning text-light form-control" type="submit" style="padding: 10px"><i class="bi bi-search"></i></button>
                            </td>
                        </tr>
                    </table>
                </form>


                <table class="table table-striped">
                    <thead class="fw-bold">
                        <tr>
                            <td>
                                Tanggal
                            </td>
                            <td>
                                Client
                            </td>
                            <td>
                                Freelancer
                            </td>
                            <td>
                                Proyek
                            </td>
                            <td>
                                Modul
                            </td>
                            <td>
                                Status
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modulDiambil as $item)
                            <tr>
                                <td>
                                    {{$item->created_at->format('d-m-Y')}}
                                </td>
                                <td>
                                    @foreach ($proyek as $itemProyek)
                                        @if ($itemProyek->proyek_id==$item->proyek_id)
                                            @foreach ($cust as $namaCust)
                                                @if ($namaCust->cust_id==$itemProyek->cust_id)
                                                    {{$namaCust->nama}}
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($cust as $namaCust)
                                        @if ($namaCust->cust_id==$item->cust_id)
                                            {{$namaCust->nama}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($proyek as $itemProyek)
                                        @if ($itemProyek->proyek_id==$item->proyek_id)
                                            {{$itemProyek->nama_proyek}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($modul as $itemModul)
                                        @if ($itemModul->modul_id==$item->modul_id)
                                            {{$itemModul->title}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if ($item->status=='selesai')
                                       <span class="text-success fw-bold">{{$item->status}}</span>
                                    @endif
                                    @if ($item->status=='pengerjaan')
                                        <span class="text-warning fw-bold">{{$item->status}}</span>
                                    @endif
                                    @if ($item->status=='dibatalkan')
                                        <span class="text-danger fw-bold">{{$item->status}}</span>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
