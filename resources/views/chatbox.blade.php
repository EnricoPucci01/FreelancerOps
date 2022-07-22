@extends('header')
@section('content')
<link rel="stylesheet" href="<?php echo asset('chatboxCSS.css')?>" type="text/css">

<div class="card bg-dark" style="height: 100%">
    <div class="menu bg-warning">
        {{-- <div class="back"><i class="fa fa-chevron-left"></i> <img src="https://i.imgur.com/DY6gND0.png"
                draggable="false" /></div> --}}
        <div class="name fw-bold"><span class="mr-3">{{$recieverName}}</span>  ( {{$topik}} )</div>
    </div>
    <ol class="chat">

        @foreach ($chat as $itemChat)
        <li>
            @if (session()->get('cust_id') == $itemChat['sender_id'])
                <li class="self">
                    <div class="avatar">
                        @if ($itemChat['sender_id']==$freelancerPic->cust_id)
                            <img src={{asset("storage/profilePic/$freelancerPic->foto")}} draggable="false"/>
                        @else
                            <img src={{asset("storage/profilePic/$clientPic->foto")}} draggable="false"/>
                        @endif

                    </div>
                    <div class="msg">
                        <p>{{$itemChat['message']}}</p>
                        <time>{{$itemChat['message_time']}}</time>
                    </div>
                </li>
            @else
                <li class="other">
                    <div class="avatar">
                        @if ($itemChat['sender_id']==$freelancerPic->cust_id)
                            <img src={{asset("storage/profilePic/$freelancerPic->foto")}} draggable="false"/>
                        @else
                            <img src={{asset("storage/profilePic/$clientPic->foto")}} draggable="false"/>
                        @endif
                    </div>
                    <div class="msg">
                        <p>{{$itemChat['message']}}
                        </p>
                        <time>{{$itemChat['message_time']}}</time>
                    </div>
                </li>
            @endif

        </li>

        @endforeach

        {{-- <li class="other">
            <div class="avatar"></div>
            <div class="msg">
                <p>Qué contexto de Góngora?
                    <emoji class="suffocated" />
                </p>
                <time>20:18</time>
            </div>
        </li> --}}

        {{-- <li class="self">
            <div class="avatar"></div>
            <div class="msg">
                <img src="https://i.imgur.com/QAROObc.jpg" draggable="false" />
                <time>20:19</time>
            </div>
        </li> --}}
    </ol>
    <div class="mt-3 mb-3" style="margin: auto;width: 50%;padding: 10px;">
        <form action={{url("/submitChat/$roomId")}} method="POST">
            @csrf
            @method('POST')
            <table  style="width: 100%">
                <tr>
                    <td>
                        <input class="textarea bg-light form-control"type="text" name='message' placeholder="Ketikan Pesan Anda Disini ..." />
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary ml-2 kirim"><h2><i class="bi bi-send"></i></h2></button>
                    </td>
                </tr>
            </table>
        </form>
    </div>


</div>



    {{-- <div class="card" style="height: 100%">
        <div class="card-body">
            <table>

                    @foreach ($chat as $itemChat)
                    <tr>
                        @if (session()->get('name') == $itemChat['sender_name'])
                            <div class="card float-end mt-2" style="width:50%">
                                <div class="card-body">
                                    <span class="fs-6"> {{$itemChat['message_time']}}</span>
                                    <span class="badge rounded-pill bg-info text-light fs-5">{{$itemChat['message']}}</span>
                                </div>
                            </div>
                        @else
                            <div class="card mb-2" style="width:50%">
                                <div class="card-body">
                                    <h5 class="card-title">{{$itemChat['sender_name']}}</h5>
                                    <span class="badge rounded-pill bg-info text-light fs-5">{{$itemChat['message']}}</span>
                                    <span class="fs-6"> {{$itemChat['message_time']}}</span>
                                </div>
                            </div>
                        @endif

                    </tr>

                    @endforeach

            </table>
        </div>
        <div class="mt-3 mb-3" style="margin: auto;width: 50%;padding: 10px;">
            <form action={{url("/submitChat/$roomId")}} method="POST">
                @csrf
                @method('POST')
                <table  style="width: 100%">
                    <tr>
                        <td>
                            <input type="text" class="form-control" name='message' placeholder="Ketikan Pesan Anda Disini ...">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

      </div> --}}
@endsection
