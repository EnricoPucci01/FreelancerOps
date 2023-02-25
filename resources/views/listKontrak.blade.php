@extends('header')
@section('content')

<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
    <div class="card mt-3 mb-3" >
        <h5 class="card-header">List Kontrak</h5>
        <ul class="nav nav-tabs mt-2">
            <li class="nav-item">
              <a class="{{$status == 'pengerjaan' ? "nav-link active" : "nav-link"}}" href={{url("/listKontrak/pengerjaan")}}>Pengerjaan</a>
            </li>
            <li class="nav-item">
              <a class="{{$status == 'selesai' ? 'nav-link active' : 'nav-link'}}" href={{url("/listKontrak/selesai")}}>Selesai</a>
            </li>
            <li class="nav-item">
              <a class="{{$status == 'dibatalkan' ? 'nav-link active' : 'nav-link'}}" href={{url("/listKontrak/dibatalkan")}}>Dibatalkan</a>
            </li>
        </ul>
        <div class="card-body" style="text-align: center">
            <table class="table table-striped">
                <thead class="fw-bold">
                    <td>
                        Tanggal
                    </td>
                    <td>
                        Kontrak
                    </td>
                    <td>
                        Status
                    </td>
                    <td>
                        Tanda Tangan
                    </td>
                    <td>

                    </td>
                </thead>
                @foreach ($listKontrak as $kontrak)
                    <tr>
                        <td>
                            {{ Carbon\Carbon::parse($kontrak['created_at'])->format('d-m-Y') }}
                        </td>
                        <td>
                            {{$kontrak['kontrak_kerja']}}

                        </td>
                        <td>
                            <p class="text-primary fw-bold">{{$kontrak['status']}}</p>
                        </td>
                        <td>
                            @if (session()->get('role')=='freelancer')
                                @if ($kontrak['freelancer_sign']!=null)
                                    <a href={{url("/esign/$kontrak[modultaken_id]")}} class="btn btn-secondary" style=" pointer-events: none;"><i class="bi bi-pen"></i></a>
                                @else
                                    <a href={{url("/esign/$kontrak[modultaken_id]")}} class="btn btn-primary"><i class="bi bi-pen"></i></a>
                                @endif

                            @else
                                @if ($kontrak['client_sign']!=null)
                                <a href={{url("/esign/$kontrak[modultaken_id]")}} class="btn btn-secondary" style=" pointer-events: none;"><i class="bi bi-pen"></i></a>
                                @else
                                    <a href={{url("/esign/$kontrak[modultaken_id]")}} class="btn btn-primary"><i class="bi bi-pen"></i></a>
                                @endif
                            @endif

                        </td>
                        <td>
                            {{-- href={{asset("/storage/kontrak/$kontrak[urlkontrak]")}} download --}}
                            <a href={{asset("/storage/kontrak/$kontrak[urlkontrak]")}} download class="btn btn-success"><i class="bi bi-box-arrow-down"></i></i></a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
</div>

@endsection
