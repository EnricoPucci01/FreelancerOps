@extends('header')
@section('content')
    <center>
        <div class="card mt-3" style="width: 80%">
            <h3 class="card-title">Laporan Pendapatan</h3>
            <div class="card-body" >
                <table class="table table-striped">
                    <thead class="fw-bold">
                        <td>
                            E-mail
                        </td>
                        <td>
                            Tanggal Tagihan
                        </td>
                        <td>
                            Status
                        </td>
                        <td>
                            Saluran
                        </td>
                        <td>
                            Jumlah
                        </td>
                        <td>
                            Service Fee
                        </td>
                        <td>
                            Grand Total
                        </td>
                    </thead>
                    <tbody>
                        @foreach ($dataPayment as $payment)
                        <tr>
                            <td>
                                {{$payment->email}}
                            </td>
                            <td>
                                {{$payment->created_at}}
                            </td>
                            <td>
                                {{$payment->status}}
                            </td>
                            <td>
                                {{$payment->payment_channel}}
                            </td>
                            <td>
                                @money($payment->amount,'IDR',true)
                            </td>
                            <td>
                                @money($payment->service_fee,'IDR',true)
                            </td>
                            <td>
                                @money($payment->grand_total,'IDR',true)
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $dataPayment->links("pagination::bootstrap-4") }}
                <a href={{url("/chartProyekTidakBayar")}} class="btn btn-primary">Lihat Grafik</a>
            </div>
        </div>
    </center>
@endsection
