@extends('header')
@section('content')
    <link rel="stylesheet" href="<?php echo asset('reviewCss.css'); ?>" type="text/css">
    <center>
        <div class="mt-3 mb-3 ml-2 mr-2">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <table>
                            <tr>
                                <td>
                                    <div class="ml-3 row" style="width: 100%">
                                        <div class="side">
                                            <div><i class='bi bi-star-fill' style='color:#ffc700'></i> 5</div>
                                        </div>
                                        <div class="middle">
                                            <div class="bar-container">
                                                <div
                                                    style="width: {{ $bintang5 }}%; height: 10px; background-color: #ffc700;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="side">
                                            <div><i class='bi bi-star-fill' style='color:#ffc700'></i> 4</div>
                                        </div>
                                        <div class="middle">
                                            <div class="bar-container">
                                                <div
                                                    style="width:{{ $bintang4 }}%; height: 10px; background-color: #ffc700;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="side">
                                            <div><i class='bi bi-star-fill' style='color:#ffc700'></i> 3</div>
                                        </div>
                                        <div class="middle">
                                            <div class="bar-container">
                                                <div
                                                    style="width: {{ $bintang3 }}%; height: 10px; background-color: #ffc700;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="side">
                                            <div><i class='bi bi-star-fill' style='color:#ffc700'></i> 2</div>
                                        </div>
                                        <div class="middle">
                                            <div class="bar-container">
                                                <div
                                                    style="width: {{ $bintang2 }}%; height: 10px; background-color: #ffc700;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="side">
                                            <div><i class='bi bi-star-fill' style='color:#ffc700'></i> 1</div>
                                        </div>
                                        <div class="middle">
                                            <div class="bar-container">
                                                <div
                                                    style="width:{{ $bintang1 }}%; height: 10px; background-color: #ffc700;">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <td style="text-align: left; text-align:center">
                                    <div style="width: 100%">
                                        <h2><i class='bi bi-star-fill' style='color:#ffc700'></i>
                                            {{ $rataRata }} <i class="text-muted" style="font-size: 25px">/5</i>
                                        </h2>
                                    </div>
                                    <p class="fw-bold text-muted">{{ $jumlahReview }} Reviews</p>
                                </td>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </center>
    <div style="margin: auto; width: 100%; padding: 10px;">
        <div style="margin: auto; width:100%; padding:10px">
            <table class="mb-3">
                @if (count($dataReview) < 1)
                    <center>
                        <h3>
                            Tidak Ada Review
                        </h3>
                    </center>
                @else
                    @foreach ($dataReview as $review)
                        <tr>
                            <div class="card" style="width: 100%; margin-top: 20px">
                                <div class="card-body">
                                    <h3>
                                        @foreach ($client as $item)
                                            @if ($review['freelancer_id'] == $item['cust_id'])
                                                {{ $item['nama'] }}
                                            @endif
                                        @endforeach
                                    </h3>
                                    <h6>
                                        @foreach ($profil as $itemprofil)
                                            @foreach ($client as $item)
                                                @if ($itemprofil->cust_id == $item['cust_id'])
                                                    {{ $itemprofil->pekerjaan }}
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </h6>
                                    <h6>
                                        @foreach ($modul as $itemModul)
                                            @if ($review['modul_id'] == $itemModul['modul_id'])
                                                <p class="text-muted">{{ $itemModul['title'] }}</p>
                                            @endif
                                        @endforeach
                                    </h6>

                                    <p>
                                        @php
                                            $star = $review['bintang'];
                                            for ($i = 0; $i < 5; $i++) {
                                                if ($star >= 1) {
                                                    echo "<i class='bi bi-star-fill' style='color:#ffc700'></i>";
                                                } elseif ($star > 0) {
                                                    echo "<i class='bi bi-star-half' style='color:#ffc700'></i>";
                                                } elseif ($star <= 0) {
                                                    echo "<i class='bi bi-star' style='color:gray'></i>";
                                                }
                                                $star--;
                                            }
                                        @endphp
                                    </p>
                                    <div class="card-text">
                                        <p class="card-text">{{ $review['review'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
@endsection
