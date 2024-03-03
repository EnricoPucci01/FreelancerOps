@extends('header')
@section('content')
    <center>
        <div class="p-3 mb-2  mt-3 alert bg-warning fw-bold" role="alert" style="width: 70%;">
            <span class="text-light">
                Proyek Rekomendasi Dari Kita Berdasarkan {{ $tipeRekomen }} !
            </span>
        </div>

        <div style="width: 70%">
            <nav class="nav nav-pills nav-fill bg-dark" style="border-radius: 5px">
                <a class="{{ $tipeRekomen == 'Kategori' ? 'nav-item nav-link fw-bold bg-warning active' : 'nav-item text-warning fw-bold nav-link' }}"
                    href={{ url('/loadRecomend/Kategori') }}><u> Kategori</u></a>
                <a class="{{ $tipeRekomen == 'Tag' ? 'nav-item nav-link fw-bold bg-warning active' : 'nav-item text-warning fw-bold nav-link' }}"
                    href={{ url('/loadRecomend/Tag') }}><u>Tag</u></a>
            </nav>
            <table>
                @if (count($recomendProyek) <= 0)
                    <div class="card mb-3" style="width: 30rem; margin-top: 20px; text-align:left">
                        <h5 class="card-title">Tidak ada rekomendasi</h5>
                    </div>
                @else
                    @foreach ($recomendProyek as $proyek)
                        <tr>
                            <div class="card mb-3" style="width: 30rem; margin-top: 20px; text-align:left">
                                <img class="card-img-top" src={{ asset("/storage/dokumen/$proyek->dokumentasi_proyek") }} >

                                <div class="card-body">
                                    <h5 class="card-title">{{$proyek->nama_proyek}}</h5>
                                    @foreach ($listkategoriJob as $katJob)
                                        @if ($katJob['kategorijob_id']==$proyek->kategorijob_id)
                                            <h6 class="card-subtitle mb-2 text-muted">{{$katJob['judul_kategori']}}</h5>
                                        @endif
                                    @endforeach
                                    <hr>
                                    <p class="card-text">{{ $proyek->desc_proyek }}</p>

                                    @if ($proyek->tipe_proyek == 'magang')
                                        @money($proyek->range_bayaran1, 'IDR', true) - @money($proyek->range_bayaran2, 'IDR', true)
                                    @else
                                        @money($proyek->total_pembayaran, 'IDR', true)
                                    @endif
                                    <hr>
                                    <p class="card-text">Dimulai:
                                        <b>{{ Carbon\Carbon::parse($proyek->start_proyek)->format('d-m-Y') }}</b> Deadline:
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
                                    <a href={{ url("/loadProyek/$proyek->proyek_id/" . session()->get('cust_id')) }}
                                        class="btn btn-primary">Lihat
                                        Detail</a>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                @endif

            </table>
        </div>
    </center>
@endsection
