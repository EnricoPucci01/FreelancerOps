@extends('header')
@section('content')
    <div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
        <div class='card mt-3'>
            <div class="card-header">
                <h4>Laporan Proyek</h4>
            </div>
            <ul class="nav nav-tabs mt-2">
                <li class="nav-item">
                    <a class="{{ $status == 'pengerjaan' ? 'nav-link active' : 'nav-link' }}"
                        href={{ url('/laporanProyekAdmin/pengerjaan') }}>Pengerjaan</a>
                </li>
                <li class="nav-item">
                    <a class="{{ $status == 'selesai' ? 'nav-link active' : 'nav-link' }}"
                        href={{ url('/laporanProyekAdmin/selesai') }}>Selesai</a>
                </li>
                <li class="nav-item">
                    <a class="{{ $status == 'dibatalkan' ? 'nav-link active' : 'nav-link' }}"
                        href={{ url('/laporanProyekAdmin/dibatalkan') }}>Dibatalkan</a>
                </li>
            </ul>
            <div style="padding: 10px">
                <form method="POST"
                    action={{ ($status == 'selesai')
                            ? url('/filterLaporanAdmin/selesai')
                            : ($status == 'dibatalkan'
                        ? url('/filterLaporanAdmin/dibatalkan')
                        : url('/filterLaporanAdmin/pengerjaan')) }}>
                    @csrf
                    @method('POST')

                    <table>
                        <tr>
                            <td>
                                <input type="date" name='dateStart' value={{ Carbon\Carbon::now()->subDays(1) }}
                                    class="form-control">
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                <input type="date" name='dateEnd' value={{ Carbon\Carbon::now() }} class="form-control">
                            </td>
                            <td>
                                <button class="btn btn-warning text-light form-control" type="submit"
                                    style="padding: 10px"><i class="bi bi-search"></i></button>
                            </td>
                        </tr>
                    </table>
                </form>
                <div style="width: 100%;">
                    <table style="width:50%;margin-left:auto; margin-right:auto">
                        <tr>
                            <td>
                                <div class="bg-danger" style="width:30px;height:20px">

                                </div>
                            </td>
                            <td>
                                15 hari atau kurang dari Deadline
                            </td>
                            <td>
                                <div class="bg-dark" style="width:30px;height:20px">

                                </div>
                            </td>
                            <td>
                                Terlambat
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="fw-bold">
                        <tr>
                            <td>
                                Tanggal Pengambilan
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
                                Deadline Proyek
                            </td>
                            <td>
                                Modul
                            </td>
                            <td>
                                Deadline Modul
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modulDiambil as $item)
                            <tr
                                @foreach ($modul as $itemModul)
                                    @if ($item->modul_id == $itemModul->modul_id)
                                        @if ((int) Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($itemModul->end)) <= 15)
                                            class="bg-danger text-light"
                                        @endif
                                        @if (Carbon\Carbon::now()->gt(Carbon\Carbon::parse($itemModul->end)))
                                            class="bg-dark text-light"
                                        @endif
                                    @endif @endforeach>
                                <td>
                                    {{ $item->created_at->format('d-m-Y') }}
                                </td>
                                <td>
                                    @foreach ($proyek as $itemProyek)
                                        @if ($itemProyek->proyek_id == $item->proyek_id)
                                            @foreach ($cust as $namaCust)
                                                @if ($namaCust->cust_id == $itemProyek->cust_id)
                                                    @foreach ($modul as $itemModul)
                                                        @if ($item->modul_id == $itemModul->modul_id)
                                                            @if ((int) Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($itemModul->end)) <= 15)
                                                                <a href={{ url("/loadProfil/v/$namaCust[cust_id]") }}><u
                                                                        class="fw-bold text-light">{{ $namaCust->nama }}</u></a>
                                                            @else
                                                                @if (Carbon\Carbon::now()->gt(Carbon\Carbon::parse($itemModul->end)))
                                                                    <a href={{ url("/loadProfil/v/$namaCust[cust_id]") }}><u
                                                                            class="fw-bold text-light">{{ $namaCust->nama }}</u></a>
                                                                @else
                                                                    <a href={{ url("/loadProfil/v/$namaCust[cust_id]") }}><u
                                                                            class="fw-bold text-dark">{{ $namaCust->nama }}</u></a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($cust as $namaCust)
                                        @if ($namaCust->cust_id == $item->cust_id)
                                            @foreach ($modul as $itemModul)
                                                @if ($item->modul_id == $itemModul->modul_id)
                                                    @if ((int) Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($itemModul->end)) <= 15)
                                                        <a href={{ url("/loadProfil/v/$namaCust[cust_id]") }}><u
                                                                class="fw-bold text-light">{{ $namaCust->nama }}</u></a>
                                                    @else
                                                        @if (Carbon\Carbon::now()->gt(Carbon\Carbon::parse($itemModul->end)))
                                                            <a href={{ url("/loadProfil/v/$namaCust[cust_id]") }}><u
                                                                    class="fw-bold text-light">{{ $namaCust->nama }}</u></a>
                                                        @else
                                                            <a href={{ url("/loadProfil/v/$namaCust[cust_id]") }}><u
                                                                    class="fw-bold text-dark">{{ $namaCust->nama }}</u></a>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($proyek as $itemProyek)
                                        @if ($itemProyek->proyek_id == $item->proyek_id)
                                            {{ $itemProyek->nama_proyek }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($proyek as $itemProyek)
                                        @if ($itemProyek->proyek_id == $item->proyek_id)
                                            {{ Carbon\Carbon::parse($itemProyek->deadline)->format('d-m-Y') }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($modul as $itemModul)
                                        @if ($itemModul->modul_id == $item->modul_id)
                                            {{ $itemModul->title }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($modul as $itemModul)
                                        @if ($itemModul->modul_id == $item->modul_id)
                                            {{ Carbon\Carbon::parse($itemModul->end)->format('d-m-Y') }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
