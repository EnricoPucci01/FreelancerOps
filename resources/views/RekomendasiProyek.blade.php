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
                @foreach ($recomendProyek as $proyek)
                    <tr>
                        <div class="card mb-3" style="width: 30rem; margin-top: 20px">
                            <div class="card-body">
                                <h5 class="card-title">{{ $proyek['nama_proyek'] }}</h5>
                                @foreach ($listkategoriJob as $katJob)
                                    @if ($katJob['kategorijob_id'] == $proyek['kategorijob_id'])
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $katJob['judul_kategori'] }}</h5>
                                    @endif
                                @endforeach
                                <p class="card-text">{{ $proyek['desc_proyek'] }}</p>
                                <hr>
                                <div class="card-text">
                                    @foreach ($listtag as $tag)
                                        @if ($proyek['proyek_id'] == $tag['proyek_id'])
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
                                <a href={{ url("/loadProyek/$proyek[proyek_id]/" . session()->get('cust_id')) }}
                                    class="btn btn-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </tr>
                @endforeach
            </table>
        </div>
    </center>
@endsection
