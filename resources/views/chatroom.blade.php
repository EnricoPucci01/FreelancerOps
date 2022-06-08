@extends('header')
@section('content')
<center>
    <table>
        <tr>
            @foreach ($chatroom as $item)
            <a href={{url("/loadChatbox/$item[room_id]")}}>
                <div class="card mt-3" style="width: 70%;">
                    <div class="card-body">
                        @foreach ($cust as $custData)
                            @if (session()->get('role')=='client')
                                @if ($custData['cust_id']==$item['freelancer_id'])
                                    <h4 class="card-title">{{$custData['nama']}}</h4>
                                @endif
                            @elseif (session()->get('role')=='freelancer')
                                @if ($custData['cust_id']==$item['client_id'])
                                    <h4 class="card-title">{{$custData['nama']}}</h4>
                                @endif
                            @endif
                        @endforeach
                        @foreach ($chat as $itemChat)
                            @if ($itemChat['room_id']==$item['room_id'])
                                <p class="card-subtitle mb-2 text-muted">{{$itemChat['message_time']}}</p>
                                <h6 class="card-subtitle mb-2">{{$itemChat['message']}}</h6>
                            @endif
                        @endforeach
                    </div>
                </div>
            </a>
            @endforeach
        </tr>
    </table>
</center>

<style>
    body {font-family: Arial, Helvetica, sans-serif;}
    * {box-sizing: border-box;}

    /* Button used to open the chat form - fixed at the bottom of the page */
    .open-button {
      background-color: #555;
      color: white;
      border: none;
      cursor: pointer;
      opacity: 0.8;
      position: fixed;
      bottom: 23px;
      right: 28px;
      width: 7%;
      height: 7%;
    }

    /* The popup chat - hidden by default */
    .chat-popup {
      display: none;
      position: fixed;
      bottom: 0;
      right: 15px;
      border: 3px solid #f1f1f1;
      z-index: 9;
    }

    /* Add styles to the form container */
    .form-container {
      max-width: 300px;
      padding: 10px;
      background-color: white;
    }

    /* Full-width textarea */
    .form-container textarea {
      width: 100%;
      padding: 15px;
      margin: 5px 0 22px 0;
      border: none;
      background: #f1f1f1;
      resize: none;
      min-height: 200px;
    }

    /* When the textarea gets focus, do something */
    .form-container textarea:focus {
      background-color: #ddd;
      outline: none;
    }

    /* Set a style for the submit/send button */
    .form-container .bton {
      background-color: #04AA6D;
      color: white;
      padding: 16px 20px;
      border: none;
      cursor: pointer;
      width: 100%;
      margin-bottom:10px;
      opacity: 0.8;
    }

    /* Add a red background color to the cancel button */

    /* Add some hover effects to buttons */
    .form-container .bton:hover, .btn:hover ,.open-button:hover {
      opacity: 1;
    }
    </style>

    <button class="btn btn-primary mt-3 ml-3 open-button" onclick="openForm()"> <h2>

        <i class="bi bi-chat-dots"></i>
        </h2>
    </button>

    <div class="chat-popup" id="myForm">
      <form action="{{url("/kirimPesan")}}" method="post" class="form-container">
        @method('POST')
        @csrf
        <table>
            <tr>
                <td style="width: 170%">
                    <h1>Chat</h1>
                </td>
                <td >
                    <button type="button" class="btn btn-danger cancel center" onclick="closeForm()">
                        <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                    </button>
                </td>
            </tr>
            </table>

            <label for="formFile" class="form-label">Kirim Pesan Ke</label>
            <input class="form-control" type="text" name='tujuan' id="formFile" placeholder="johndoe@gmail.com">
            <label for="desc" class="form-label mt-3" >Pesan Anda</label>
            <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="pesan" maxlength="1200" id='desc' placeholder="Ketikan Pesan Disini"></textarea>


        <button type="submit" class="btn bton btn-success"><i class="bi bi-send"></i></button>

      </form>
    </div>

    <script>
    function openForm() {
      document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
      document.getElementById("myForm").style.display = "none";
    }
    </script>

@endsection
