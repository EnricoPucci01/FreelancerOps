@extends('header')
@section('content')
    <center>
        <table>
            @if (count($cv)<=0)
                <div class="card mb-3" style="width: 70%; margin-top: 20px">
                    <h1 class='card-title mt-3 mb-3'>Tidak Ada Pendaftar Untuk Saat Ini...</h1>
                </div>
            @else
                @foreach ($cv as $itemCV)
                    <tr>
                        <div class="card mb-3" style="width: 30rem; margin-top: 20px">
                            <div class="card-body">
                                @foreach ($applicantList as $applicant)
                                    @if ($applicant['cust_id']==$itemCV['cust_id'])
                                        <h5 class="card-title">{{$applicant['nama']}}</h5>
                                        <p class="card-text">Contact: {{$applicant['nomorhp']}}</p>
                                        <hr>
                                        <div class="card-text">
                                            {{$itemCV['applicant_desc']}}
                                        </div>
                                        <hr>
                                        <a href="{{url("/previewcv/$itemCV[cv]")}}" class="btn btn-primary">Lihat CV</a>
                                        <a href="{{url("/loadProfilApplicant/c/$itemCV[cust_id]/$itemCV[applicant_id]/$modulId/$proyekId")}}" class="btn btn-primary">Lihat Profil</a>
                                        {{-- <a href="{{url("/previewcv/$itemCV[cv]")}}" class="btn btn-success">Terima</a>
                                        <a href="{{url("/loadProfilApplicant/c/$itemCV[cust_id]/$itemCV[applicant_id]/$modulId/$proyekId")}}" class="btn btn-danger">Tolak</a> --}}
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </tr>
                @endforeach
            @endif

        </table>
        <a href={{url("/loadDetailProyekClient/$proyekId/c")}} class="btn btn-secondary mt-3 mb-3">Kembali</a>
    </center>


@endsection
