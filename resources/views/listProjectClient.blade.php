@extends('header')
@section('content')
    <style type="text/css">
        .pagination {
            justify-content: center;
        }

        .page-item.active .page-link {
            color: #fff;
            background-color: yellow;
            border-color: yellow;
        }
    </style>
    <center>

        <table>
            @foreach ($listproyek as $proyek)
                {{-- -------------------------------------------------MODAL----------------------------------------------- --}}
                {{-- Modal Post --}}
                <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPost{{ $proyek->proyek_id }}"
                    style="text-align: left">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel2">
                                    Peringatan!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Proyek <b>{{ $proyek->nama_proyek }} </b> akan anda Non-Aktifkan? Anda Yakin?
                            </div>
                            <div class="modal-footer">
                                <a href={{url("/nonaktifkanProyek/$proyek->proyek_id/false")}} class="btn btn-primary">Ya</a>
                                <button type="button" data-bs-dismiss="modal" class="btn btn-dark">Tidak</button>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="modal fade" tabindex="-1"aria-hidden="true" id="modalPostAktif{{ $proyek->proyek_id }}"
                    style="text-align: left">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel2">
                                    Peringatan!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Proyek <b>{{ $proyek->nama_proyek }} </b> akan anda Aktifkan? Anda Yakin?
                            </div>
                            <div class="modal-footer">
                                <a href={{url("/nonaktifkanProyek/$proyek->proyek_id/true")}} class="btn btn-primary">Ya</a>
                                <button type="button" data-bs-dismiss="modal" class="btn btn-dark">Tidak</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ---------------------------------------------------------------------------------------------------- --}}



                <tr>
                    <div class="card mt-3 mb-3" style="width: 30rem">
                        <div class="card-header">
                            <div style="float: left">
                                <h5 class="card-title" style="text-align: left">{{ $proyek->nama_proyek }}</h5>
                                @foreach ($listkategoriJob as $katJob)
                                    @if ($katJob['kategorijob_id'] == $proyek->kategorijob_id)
                                        <h6 class="card-subtitle mb-2 text-muted" style="text-align: left">
                                            {{ $katJob['judul_kategori'] }}</h5>
                                    @endif
                                @endforeach
                                <p class="card-subtitle mb-2 " style="text-align: left">
                                    Total Bayaran:
                                    @if($proyek->tipe_proyek == 'normal')
                                    <b>
                                        @money($proyek->total_pembayaran,'IDR',true)
                                    </b>
                                    @else
                                    <b>
                                        @money($proyek->range_bayaran1,'IDR',true) - @money($proyek->range_bayaran2,'IDR',true)
                                    </b>
                                    @endif
                                </p>
                            </div>
                            <div style="float: right">
                                @if ($proyek->project_active == 'false')
                                    <h5 class=" card-title text-danger">Tidak Aktif</h5>
                                @else
                                    <h5 class=" card-title text-success">Aktif</h5>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">

                            <p class="card-text">{{ $proyek['desc_proyek'] }}</p>

                            <p class="card-text">Dimulai Pada:
                                <b>{{ Carbon\Carbon::parse($proyek->start_proyek)->format('d-m-Y') }}</b>
                            </p>
                            <p class="card-text">Berakhir Pada:
                                <b>{{ Carbon\Carbon::parse($proyek->deadline)->format('d-m-Y') }}</b>
                            </p>
                            <hr>
                            <div class="card-text">
                                @foreach ($listtag as $tag)
                                    @if ($proyek->proyek_id == $tag['proyek_id'])
                                        @foreach ($listkategori as $kategori)
                                            @if ($tag['kategori_id'] == $kategori['kategori_id'])
                                                <p class="badge rounded-pill bg-primary" style="margin-bottom: 0px">
                                                    #{{ $kategori['nama_kategori'] }}
                                                </p>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                            <hr>
                            <a href="{{ url("/loadDetailProyekClient/$proyek->proyek_id/c") }}"
                                class="btn btn-primary fw-bold text-light">Lihat Lebih Detail</a>
                            @if ($proyek->project_active == "true")
                            <Button type="button" data-bs-target="#modalPost{{ $proyek->proyek_id }}"
                                data-bs-toggle="modal"
                                class="btn btn-danger fw-bold text-light">Non-Aktifkan</Button>
                            @else
                            <Button type="button" data-bs-target="#modalPostAktif{{ $proyek->proyek_id }}"
                                data-bs-toggle="modal"
                                class="btn btn-success fw-bold text-light">Aktifkan</Button>
                            @endif

                        </div>
                    </div>
                </tr>
            @endforeach
        </table>
        {{ $listproyek->links('pagination::bootstrap-4') }}
    </center>
@endsection
