@extends('header')
@section('content')
    <div style="width:100%; top:0; bottom: 0; left: 0; right: 0; margin: auto; padding:15px">

        <div class="card mt-3 mb-3">
            <h5 class="card-header">Laporan Proyek Belum Terbayar</h5>


            <div class="card-body" style="text-align: center">
                <table class="table table-striped">
                    <thead class="fw-bold">
                        <td>
                            Client
                        </td>
                        <td>
                            Email
                        </td>
                        <td>
                            Nomor HP
                        </td>
                        <td>
                            Judul Modul
                        </td>
                        <td>
                            Harga Modul
                        </td>
                        <td>
                            Biaya Pelayanan
                        </td>
                        <td>
                            Total
                        </td>
                        <td>
                            Tanggal Tagihan
                        </td>
                        <td>
                            Terlambat
                        </td>
                        <td>
                            Status
                        </td>
                        <td>
                            Action
                        </td>
                    </thead>
                    <tbody>
                        @foreach ($laporanBelumBayar as $payment)
                            <tr>
                                <td>
                                    {{ $payment['namaClient'] }}
                                </td>
                                <td>
                                    {{ $payment['email'] }}
                                </td>
                                <td>
                                    {{ $payment['hp'] }}
                                </td>
                                <td>
                                    {{ $payment['judul'] }}
                                </td>
                                <td>
                                    <p class="fw-bold">@money($payment['hargamodul'], 'IDR', true)</p>
                                </td>
                                <td>
                                    <p class="fw-bold">@money($payment['servicefee'], 'IDR', true)</p>
                                </td>
                                <td>
                                    <p class="fw-bold">@money($payment['grand'], 'IDR', true)</p>
                                </td>
                                <td>
                                    {{ Carbon\Carbon::parse($payment['penagihan'])->format('d-m-Y') }}
                                </td>
                                <td>
                                    {{ Carbon\Carbon::parse(Carbon\Carbon::now())->diffInDays(Carbon\Carbon::parse($payment['penagihan'])) }}
                                    Hari
                                </td>
                                <td>
                                    {{ $payment['stat'] }}
                                </td>
                                <td>
                                    @if ($payment['stat'] == 'Paid')
                                        <a href={{ url("/sendEmail/$payment[email]/unClosedPayment") }} class="btn btn-warning">E-Mail</a>
                                        <a href={{ url("/teruskanPembayaran/$payment[idPay]") }} class="btn btn-success">Tutup Pembayaran</a>
                                    @else
                                        <a href={{ url("/sendEmail/$payment[email]/unpaid") }} class="btn btn-warning">E-Mail</a>
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
