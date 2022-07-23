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
                        @if ($itemChat['sender_id']==$recieverPic->cust_id)
                            <img src={{asset("storage/profilePic/$recieverPic->foto")}} draggable="false"/>
                        @else
                            <img src={{asset("storage/profilePic/$senderPic->foto")}} draggable="false"/>
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
                        @if ($itemChat['sender_id']==$recieverPic->cust_id)
                            <img src={{asset("storage/profilePic/$recieverPic->foto")}} draggable="false"/>
                        @else
                            <img src={{asset("storage/profilePic/$senderPic->foto")}} draggable="false"/>
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
@endsection
