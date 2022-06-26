@extends('header')
@section('content')

<center>
    <div class="mt-3 mb-3 ml-2 mr-2">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
                <table style="width: 70%">
                            <tr style="text-align: left;">
                                <td>
                                    <h2><i class='bi bi-star-fill' style='color:#ffc700'></i> {{$rataRata}}</h2>
                                </td>
                                <td >
                                    <h5 class="mt-2 text-muted">/5</h5>
                                </td>
                                <td>
                                    <div style="margin-left: 50%">
                                        <table>
                                            <tr>
                                                <td>
                                                    <i class='bi bi-star-fill' style='color:#ffc700'></i> 5
                                                </td>
                                                <td>
                                                    5
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class='bi bi-star-fill' style='color:#ffc700'></i> 4
                                                </td>
                                                <td>
                                                    4
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class='bi bi-star-fill' style='color:#ffc700'></i>  3
                                                </td>
                                                <td>
                                                    3
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class='bi bi-star-fill' style='color:#ffc700'></i>   2
                                                </td>
                                                <td>
                                                    2
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class='bi bi-star-fill' style='color:#ffc700'></i>   1
                                                </td>
                                                <td>
                                                    1
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="card-text text-muted fw-bold">Total Review:  {{$jumlahReview}}</p>
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
                        @foreach ($dataReview as $review)
                            <tr>
                                <div class="card" style="width: 100%; margin-top: 20px">
                                    <div class="card-body">
                                        <h3 >
                                            @foreach ($client as $item)
                                                @if ($review['client_id']==$item['cust_id'])
                                                    {{$item['nama']}}
                                                @endif
                                            @endforeach
                                        </h3>
                                        <h6>
                                            @foreach ($profil as $itemprofil)
                                                @foreach ($client as $item)
                                                    @if ($itemprofil->cust_id==$item['cust_id'])
                                                        {{$itemprofil->pekerjaan}}
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </h6>
                                        <h6>
                                            @foreach ($modul as $itemModul)
                                                @if ($review['modul_id']==$itemModul['modul_id'])
                                                    <p class="text-muted">{{$itemModul['title']}}</p>
                                                @endif
                                            @endforeach
                                        </h6>

                                            <p>
                                                @php
                                                $star=$review['bintang'];
                                                for($i = 0; $i < 5; $i++){
                                                    if($star>=1){
                                                        echo"<i class='bi bi-star-fill' style='color:#ffc700'></i>";
                                                    }
                                                    else if($star>0){
                                                        echo"<i class='bi bi-star-half' style='color:#ffc700'></i>";
                                                    }
                                                    else if($star<=0){
                                                        echo"<i class='bi bi-star' style='color:gray'></i>";
                                                    }
                                                    $star--;
                                                }
                                                @endphp
                                            </p>
                                        <div class="card-text">
                                            <p class="card-text">{{$review['review_desc']}}</p>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>


@endsection
