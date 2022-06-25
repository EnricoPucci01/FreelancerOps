@extends('header')
@section('content')

<center>
    <div class="mt-3 mb-3 ml-2 mr-2">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
                <table>
                    <tr>
                        <td>
                            <h2><i class='bi bi-star-fill' style='color:#ffc700'></i> {{$rataRata}}</h2>
                        </td>
                        <td>
                            <h5 class="mt-2 text-muted">/5</h5>
                        </td>
                    </tr>
                </table>
              <p class="card-text text-muted fw-bold">Total Rating: {{$totalBintang}} | Total Review:  {{$jumlahReview}} | Proyek Selesai: {{$proyekSelesai}}</p>
            </div>
          </div>
        </div>
    </div>
</center>
            <div style="margin: auto; width: 60%; padding: 10px;">
                <div style="margin: auto; width:65%; padding:10px">
                    <table class="mb-3">
                        @foreach ($dataReview as $review)
                            <tr>
                                <div class="card" style="width: 30rem; margin-top: 20px">
                                    <div class="card-body">
                                        <h3 >
                                            @foreach ($client as $item)
                                                @if ($review['client_id']==$item['cust_id'])
                                                    {{$item['nama']}}
                                                @endif
                                            @endforeach
                                        </h3>

                                        <h5 >
                                            @foreach ($modul as $itemModul)
                                                @if ($review['modul_id']==$itemModul['modul_id'])
                                                    <p class="text-muted">{{$itemModul['title']}}</p>
                                                @endif
                                            @endforeach
                                        </h5>
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
                                        <hr>
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
