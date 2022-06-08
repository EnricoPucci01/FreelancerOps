@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
        <div class='card mt-3'>
            <div class="card-header">
               <h4>Laporan Proyek</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="fw-bold">
                        <tr>
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
