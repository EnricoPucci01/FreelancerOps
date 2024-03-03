@extends('header')
@section('content')
    <link rel="stylesheet" href="<?php echo asset('reviewCss.css'); ?>" type="text/css">
    <style type="text/css">
        .pagination {
            justify-content: center;
        }

        .sidebar {
            margin: 0;
            padding: 0;
            width: 220px;
            background-color: #f1f1f1;
            position: absolute;
            height: 35%;
            overflow: auto;
        }

        .sidebar a {
            display: block;
            color: black;
            padding: 16px;
            text-decoration: none;
        }

        .sidebar a.active {
            background-color: #e7880d;
            color: white;
            font-weight: bold;
        }

        .sidebar a:hover:not(.active) {
            background-color: #e7880d;
            color: white;
        }

        div.content {
            margin-left: 200px;
            padding: 1px 16px;
            height: 1000px;
        }

        @media screen and (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                float: left;
            }

            div.content {
                margin-left: 0;
            }
        }

        @media screen and (max-width: 400px) {
            .sidebar a {
                text-align: center;
                float: none;
            }
        }

        .center {
            width: 100%;
            padding: 2px;
        }
    </style>
    <center>
        @if (session()->get('role') == 'freelancer')
            <div class="p-3 mb-2  mt-3 alert bg-warning fw-bold" role="alert" style="width: 70%;">
                <a href={{ url('/loadRecomend/Kategori') }} class="text-dark">
                    Lihat Rekomendasi Proyek Dari Kita <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endif
        <div>
            <form action={{ url('/cariproyek') }}>
                <div>
                    <table style="width: 70%; margin-top:10px">
                        <tr>
                            <td style="width: 20%">
                                <select class="selectpicker form-control" data-style="btn-default btn-lg"
                                    data-live-search="true" id="kategori" name="kategori_browse[]" multiple="multiple">
                                    @foreach ($listkategori as $kategori)
                                        <option value="{{ $kategori['kategori_id'] }}">{{ $kategori['nama_kategori'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="width: 25%">
                                <select class="selectpicker form-control" data-style="btn-default btn-lg"
                                    data-live-search="true" name="kota" id="Kota">
                                    <option value="pilih kota" disabled selected value>Pilih kota </option>
                                    <option value="Banda Aceh">Banda Aceh</option>
                                    <option value="Langsa">Langsa</option>
                                    <option value="Lhokseumawe">Lhokseumawe</option>
                                    <option value="Sabang">Sabang</option>
                                    <option value="Subulussalam">Subulussalam</option>
                                    <option value="Denpasar">Denpasar</option>
                                    <option value="Pangkal Pinang">Pangkal Pinang</option>
                                    <option value="Cilegon">Cilegon</option>
                                    <option value="Serang">Serang</option>
                                    <option value="Tangerang Selatan">Tangerang Selatan</option>
                                    <option value="Tangerang">Tangerang</option>
                                    <option value="Bengkulu">Bengkulu</option>
                                    <option value="Yogyakarta">Yogyakarta</option>
                                    <option value="Gorontalo">Gorontalo</option>
                                    <option value="Jakarta Barat">Jakarta Barat</option>
                                    <option value="Jakarta Pusat">Jakarta Pusat</option>
                                    <option value="Jakarta Selatan">Jakarta Selatan</option>
                                    <option value="Jakarta Timur">Jakarta Timur</option>
                                    <option value="Jakarta Utara">Jakarta Utara</option>
                                    <option value="Sungai Penuh">Sungai Penuh</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Bekasi">Bekasi</option>
                                    <option value="Bogor">Bogor</option>
                                    <option value="Cimahi">Cimahi</option>
                                    <option value="Cirebon">Cirebon</option>
                                    <option value="Depok">Depok</option>
                                    <option value="Sukabumi">Sukabumi</option>
                                    <option value="Tasikmalaya">Tasikmalaya</option>
                                    <option value="Banjar">Banjar</option>
                                    <option value="Magelang">Magelang</option>
                                    <option value="Pekalongan">Pekalongan</option>
                                    <option value="Salatiga">Salatiga</option>
                                    <option value="Semarang">Semarang</option>
                                    <option value="Surakarta">Surakarta</option>
                                    <option value="Tegal">Tegal</option>
                                    <option value="Batu">Batu</option>
                                    <option value="Blitar">Blitar</option>
                                    <option value="Kediri">Kediri</option>
                                    <option value="Madiun">Madiun</option>
                                    <option value="Malang">Malang</option>
                                    <option value="Mojokerto">Mojokerto</option>
                                    <option value="Pasuruan">Pasuruan</option>
                                    <option value="Probolinggo">Probolinggo</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Pontianak">Pontianak</option>
                                    <option value="Singkawang">Singkawang</option>
                                    <option value="Banjarbaru">Banjarbaru</option>
                                    <option value="Banjarmasin">Banjarmasin</option>
                                    <option value="Palangkaraya">Palangkaraya</option>
                                    <option value="Balikpapan">Balikpapan</option>
                                    <option value="Bontang">Bontang</option>
                                    <option value="Samarinda">Samarinda</option>
                                    <option value="Tarakan">Tarakan</option>
                                    <option value="Batam">Batam</option>
                                    <option value="Tanjungpinang">Tanjungpinang</option>
                                    <option value="Bandar Lampung">Bandar Lampung</option>
                                    <option value="Metro">Metro</option>
                                    <option value="Ternate">Ternate</option>
                                    <option value="Tidoro Kepulauan">Tidoro Kepulauan</option>
                                    <option value="Ambon">Ambon</option>
                                    <option value="Tual">Tual</option>
                                    <option value="Bima">Bima</option>
                                    <option value="Mataram">Mataram</option>
                                    <option value="Kupang">Kupang</option>
                                    <option value="Sorong">Sorong</option>
                                    <option value="Jayapura">Jayapura</option>
                                    <option value="Dumai">Dumai</option>
                                    <option value="Pekanbaru">Pekanbaru</option>
                                    <option value="Makassar">Makassar</option>
                                    <option value="Palopo">Palopo</option>
                                    <option value="Parepare">Parepare</option>
                                    <option value="Palu">Palu</option>
                                    <option value="Baubau">Baubau</option>
                                    <option value="Kendari">Kendari</option>
                                    <option value="Bitung">Bitung</option>
                                    <option value="Kotamobagu">Manado</option>
                                    <option value="Manado">Manado</option>
                                    <option value="Tomohon">Tomohon</option>
                                    <option value="Bukittinggi">Bukittinggi</option>
                                    <option value="Padang">Padang</option>
                                    <option value="Padang Panjang">Padang Panjang</option>
                                    <option value="Pariaman">Pariaman</option>
                                    <option value="Payakumbuh">Payakumbuh</option>
                                    <option value="Sawahlunto">Sawahlunto</option>
                                    <option value="Solok">Solok</option>
                                    <option value="Lubuklinggau">Lubuklinggau</option>
                                    <option value="Pagar Alam">Pagar Alam</option>
                                    <option value="Palembang">Palembang</option>
                                    <option value="Prabumulih">Prabumulih</option>
                                    <option value="Sekayu">Sekayu</option>
                                    <option value="Binjai">Binjai</option>
                                    <option value="Gunungsitoli">Gunungsitoli</option>
                                    <option value="Medan">Medan</option>
                                    <option value="Padang Sidempuan">Padang Sidempuan</option>
                                    <option value="Pematangsiantar">Pematangsiantar</option>
                                    <option value="Sibolga">Sibolga</option>
                                    <option value="Tanjungbalai">Tanjungbalai</option>
                                    <option value="Tebing Tinggi">Tebing Tinggi</option>
                                </select>
                            </td>
                            <td style="width: 10%">
                                <select class="form-control" name="tipe_proyek">
                                    <option value="normal" selected>Normal</option>
                                    <option value="magang">Magang</option>
                                </select>
                            </td>
                            <td style="width: 30%">
                                <input placeholder="Judul Proyek" type="text" name='searchProyek' value=''
                                    class="form-control">
                            </td>
                            <td style="width: 5%">
                                <button type="submit" class="btn btn-warning form-control"><i
                                        class="bi bi-search"></i></button>
                            </td>
                            <td style="width: 5%">
                                <a href={{ url('/browse') }} class="btn btn-secondary form-control"
                                    style="padding-top:10px"><i class="bi bi-arrow-clockwise"></i></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <table>
            @foreach ($listproyek as $proyek)
                <tr>
                    <div class="card mb-3" style="width: 30rem; margin-top: 20px; text-align:left">
                        <img class="card-img-top" src={{ asset("/storage/dokumen/$proyek->dokumentasi_proyek") }}>

                        <div class="card-body">
                            <h5 class="card-title">{{ $proyek->nama_proyek }}</h5>
                            @foreach ($listkategoriJob as $katJob)
                                @if ($katJob['kategorijob_id'] == $proyek->kategorijob_id)
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $katJob['judul_kategori'] }}</h5>
                                @endif
                            @endforeach
                            <hr>
                            <p class="card-text">{{ $proyek->desc_proyek }}</p>

                            @if ($proyek->tipe_proyek == 'normal')
                                <p class="card-text">Bayaran: <b> @money($proyek->total_pembayaran, 'IDR', true)</b></p>
                            @endif
                            @if ($proyek->tipe_proyek == 'magang')
                                <p class="card-text">Bayaran: <b>@money($proyek->range_bayaran1, 'IDR', true)</b> - <b>@money($proyek->range_bayaran2, 'IDR', true)</b></p>
                            @endif

                            <p class="card-text">Kota: <b> {{ $proyek->daerah_proyek }}</b></p>
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
                            <a href="{{ url("/loadProyek/$proyek->proyek_id/$custId") }}" class="btn btn-primary">Lihat
                                Detail</a>
                        </div>
                    </div>
                </tr>
            @endforeach
        </table>
        {{ $listproyek->links('pagination::bootstrap-4') }}
    </center>
@endsection

@section('script')
    <script>
        (function() {
            Notification.requestPermission().then((result) => {
                if (result === "granted") {
                    setInterval(() => {
                        navigator.serviceWorker.ready.then(function(sw) {
                            let notification = {
                                title: 'Project Baru',
                                options: {
                                    body: 'Silahkan muat ulang halaman untuk melihat!'
                                }
                            };
                            navigator.serviceWorker.controller.postMessage(notification);
                        });
                    }, 5000);
                }
            });
        })();
    </script>
@endsection
