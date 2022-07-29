@extends('header')
@section('content')

{{-- @if (session()->has('filePDF'))
    <meta http-equiv="refresh" content="5;url={{ url('/downloadKontrak/'.session()->get('filePDF')) }}">
@endif --}}

<div style="margin: 20px 20px 20px 20px">
        <h1>{{$dataproyek['nama_proyek']}}</h1>
        <hr>
        <div>
            <h6 class="text-start">{{$dataproyek['desc_proyek']}}</h6>
        </div>

        @if ($dataproyek['tipe_proyek']=='magang')
            <h5 class="text-start mt-3">Rentang Bayaran</h5>
            <h6 class="text-start">@money($dataproyek['range_bayaran1'],'IDR',true) - @money($dataproyek['range_bayaran2'],'IDR',true)</h6>
        @else
            <h5 class="text-start mt-3">Total Bayaran</h5>
            <h6 class="text-start">@money($dataproyek['total_pembayaran'],'IDR',true)</h6>
        @endif
            <hr>
        <h5 class="text-start">Deadline: {{$dataproyek['deadline']}}</h5>
    <hr>
        <table class="table table-striped">
            <tr>
                <td>
                    Nama
                </td>
                <td>
                    Deskripsi
                </td>
                <td>
                    Bayaran
                </td>
                <td>
                    Deadline
                </td>
                <td>
                    Jumlah Pendaftar
                </td>
                @if ($accessor=='c')
                    <td>

                    </td>
                @endif
            </tr>
            <input type="hidden" name='id_proyek' value="{{$dataproyek['proyek_id']}}">

            @foreach ($datamodul as $modul)
                <tr>
                    <td>
                        {{$modul['title']}}
                    </td>
                    <td>
                        {{$modul['deskripsi_modul']}}
                    </td>
                    <td>
                        @if ($dataproyek['tipe_proyek']=='magang')
                            @money($modul['bayaran_min'],'IDR',true) - @money($modul['bayaran_max'],'IDR',true)
                        @else
                            @money($modul['bayaran'],'IDR',true)
                        @endif

                    </td>
                    <td>
                        {{$modul['end']}}
                    </td>
                    <td>
                        -
                    </td>
                    <td>
                        @if ($accessor=='c')
                            @if ($modul['status']=='taken')
                                @foreach ($modulDiambil as $diambil)
                                    @if ($diambil['modul_id']==$modul['modul_id'])
                                        <a class='btn btn-primary btn-sm' href={{url("/loadProgress/$modul[modul_id]/$diambil[modultaken_id]")}}>Lihat Progress</a>
                                    @endif

                                @endforeach
                            @endif

                            @if ($modul['status']=='not taken')
                                <a class='btn btn-warning' href={{url("/loadApplicantModul/$modul[modul_id]/$dataproyek[proyek_id]/$id")}}>Lihat Pendaftar</a>
                            @endif

                            @if ($modul['status']=="finish")
                                @if ($dataproyek['tipe_proyek']=='magang' && !collect($datapayment)->contains('modul_id',$modul['modul_id']))
                                    @foreach ($modulDiambil as $diambil)
                                        @if ($diambil['modul_id']==$modul['modul_id'])
                                            <a class='btn btn-primary btn-sm disabled' href={{url("/loadProgress/$modul[modul_id]/$diambil[modultaken_id]")}}>Lihat Progress</a>
                                        @endif
                                    @endforeach
                                    <a class='btn btn-success btn-sm ' href={{url("/loadPembayaranMagang/$modul[modul_id]")}}>Bayar</a>
                                @else
                                    {{-- <a class='btn btn-success' href={{url("/createInvoice/$modul[modul_id]")}}>Bayar</a> --}}
                                    @foreach ($datapayment as $pay)
                                    @if ($pay['modul_id']==$modul['modul_id'])
                                        @if ($pay['status']=='unpaid')
                                                @foreach ($modulDiambil as $diambil)
                                                    @if ($diambil['modul_id']==$modul['modul_id'])
                                                        <a class='btn btn-primary btn-sm disabled' href={{url("/loadProgress/$modul[modul_id]/$diambil[modultaken_id]")}}>Lihat Progress</a>
                                                    @endif
                                                @endforeach
                                            <a class='btn btn-success btn-sm ' href={{url("/loadPembayaran/$modul[modul_id]")}}>Bayar</a>
                                        @endif

                                        @if ($pay['status']=='Paid')
                                            @foreach ($modulDiambil as $diambil)
                                                @if ($diambil['modul_id']==$modul['modul_id'])
                                                    <a class='btn btn-primary btn-sm' href={{url("/loadProgress/$modul[modul_id]/$diambil[modultaken_id]")}}>Lihat Progress</a>
                                                @endif
                                            @endforeach
                                            <button class="btn btn-info btn-sm" data-bs-target={{'#modalTutup'.$pay['modul_id']}} data-bs-toggle="modal">Tutup Modul</button>

                                                {{---------------------------------------------------MODAL-------------------------------------------------}}
                                                    {{-- Modal Tutup --}}
                                                    <div class="modal fade" tabindex="-1" id={{'modalTutup'.$pay['modul_id']}} aria-hidden="true" >
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalToggleLabel2">Tutup Modul?</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Anda yakin akan melakukan permohonan penutupan untuk modul <b>{{$modul['title']}}</b>? <br>
                                                                Jika modul ditutup maka modul akan di anggap selesai dan pembayaran akan di teruskan ke pihak freelancer. <br>

                                                                Tekan <b>Ya</b> untuk melanjutkan proses penutupan modul.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a class='btn btn-info btn-sm' href={{url("/closeModul/$pay[payment_id]")}}>Ya</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {{-------------------------------------------------------------------------------------------------------}}
                                        @endif

                                        @if ($pay['status']=='Completed')
                                            @foreach ($modulDiambil as $diambil)
                                                @if ($diambil['modul_id']==$modul['modul_id'])
                                                <a class='btn btn-primary btn-sm' href={{url("/loadProgress/$modul[modul_id]/$diambil[modultaken_id]")}}>Lihat Progress</a>
                                                @endif
                                            @endforeach
                                            <a class='btn btn-success btn-sm ' href={{url("/review/$modul[modul_id]/$pay[cust_id]/$dataproyek[cust_id]")}}>Review</a>
                                        @endif
                                    @endif
                                    @endforeach
                                @endif




                            @endif

                        @endif
                </tr>
            @endforeach

        </table>
        <center>
            <a href={{url("/listprojectclient/$id")}} class="btn btn-secondary"> Kembali </a>
        </center>


</div>
@endsection
