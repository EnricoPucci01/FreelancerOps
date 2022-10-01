@extends('header')
@section('content')
    <div style="  margin: auto;
width: 50%;
padding: 10px;">
        <table>
            @foreach ($chatroom as $item)
                <tr>
                    <a href={{ url("/loadChatbox/$item[room_id]") }}>
                        <div class="card mt-3" style="width: 100%;">
                            <div class="card-body">
                                @if (session()->get('cust_id') == $item['sender_id'])
                                    @foreach ($cust as $custData)
                                        @if ($custData['cust_id'] == $item['reciever_id'])
                                            <h4 class="card-title">{{ $custData['nama'] }} <span
                                                    class="ml-3 badge badge-danger">{{ $item['unread_sender'] }}</span></h4>
                                        @endif
                                    @endforeach
                                @elseif (session()->get('cust_id') == $item['reciever_id'])
                                    @foreach ($cust as $custData)
                                        @if ($custData['cust_id'] == $item['sender_id'])
                                            <h4 class="card-title">{{ $custData['nama'] }} <span
                                                    class="ml-3 badge badge-danger">{{ $item['unread_reciever'] }}</span>
                                            </h4>
                                        @endif
                                    @endforeach
                                @endif

                                <h6 class="card-subtitle mb-2">{{ $item['topik_proyek'] }}</h6>

                                @foreach ($chat as $itemChat)
                                    @if ($itemChat['room_id'] == $item['room_id'])
                                        <p class="card-subtitle mb-2 text-muted"> <span class="mr-3"
                                                style="font-size: 20px">{{ $itemChat['message'] }}</span>
                                            -{{ $itemChat['message_time'] }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </a>
                </tr>
            @endforeach
        </table>
    </div>



    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

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
            margin-bottom: 10px;
            opacity: 0.8;
        }

        /* Add a red background color to the cancel button */

        /* Add some hover effects to buttons */
        .form-container .bton:hover,
        .btn:hover,
        .open-button:hover {
            opacity: 1;
        }
    </style>

    <button class="btn btn-primary mt-3 ml-3 open-button" onclick="openForm()">
        <h2>

            <i class="bi bi-chat-dots"></i>
        </h2>
    </button>

    <div class="chat-popup" id="myForm">
        <form action="{{ url('/kirimPesan') }}" method="post" class="form-container">
            @method('POST')
            @csrf
            <table>
                <tr>
                    <td style="width: 170%">
                        <h1>Chat</h1>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger cancel center" onclick="closeForm()">
                            <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                        </button>
                    </td>
                </tr>
            </table>

            <label for="formFile" class="form-label">Kirim Pesan Ke</label>
            <select class="form-select" name="reciever" aria-label="Default select example">
                @if (session()->get('role') == 'client')
                    @foreach ($dataModulDiambil as $key)
                        @foreach ($modulData as $keyModul)
                            @if ($keyModul['modul_id'] == $key['modul_id'])
                                @foreach ($customerData as $keyCust)
                                    @if ($keyCust['cust_id'] == $key['cust_id'])
                                        <option
                                            value={{ $keyCust['cust_id'] . '_' . str_replace(' ', '%20', $keyModul['title']) }}>
                                            {{ $keyCust['nama'] . ' (' . $keyModul['title'] . ')' }}</option>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                @else
                    @foreach ($dataModulDiambil as $key)
                        @foreach ($dataProyek as $keyProyek)
                            @if ($key['proyek_id'] == $keyProyek['proyek_id'])
                                @foreach ($modulData as $keyModul)
                                    @if ($keyModul['modul_id'] == $key['modul_id'])
                                        @foreach ($customerData as $keyCust)
                                            @if ($keyCust['cust_id'] == $keyProyek['cust_id'])
                                                <option
                                                    value={{ $keyCust['cust_id'] . '_' . str_replace(' ', '%20', $keyModul['title']) }}>
                                                    {{ $keyCust['nama'] . ' (' . $keyModul['title'] . ')' }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                @endif

            </select>
            <label for="desc" class="form-label mt-3">Pesan Anda</label>
            <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="pesan" maxlength="1200" id='desc'
                placeholder="Ketikan Pesan Disini"></textarea>


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
        const user_id = "{{ session()->get('cust_id') }}";
        const messaging = firebase.messaging();
        //const messaging = getMessaging(app);
        messaging.usePublicVapidKey(
            'BCCRM4rKZ7m8pW5ISWilGM1JLAKzMYdXvcpVLw3OzAaivycLXveeMDfc7Wc4wF7o1UwJDY3ixm13YLIkUho-WKI');


        function sendTokenToServer(fcm_token) {
            console.log(fcm_token);
            axios.post('/api/save-token', {
                fcm_token,
                user_id
            }).then(res => {
                console.log(res);
            });
        }

        function retrieveToken() {
            messaging.getToken().then((currentToken) => {
                if (currentToken) {
                    sendTokenToServer(currentToken);
                    //updateUIForPushEnabled(currentToken);
                } else {
                    // Show permission request UI
                    // console.log('No registration token available. Request permission to generate one.');
                    alert('Please Allow The Notification To Use Chat.');
                    // updateUIForPushPermisionRequeired();
                    // setTokenSentToServer(false);
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
                // ...
            });
        }

        retrieveToken();

        messaging.onTokenRefresh(() => {
            retrieveToken();
        });

        messaging.onMessage((payload) => {
            console.log('Message recieved');
            console.log(payload);

            location.reload();
        })
    </script>

@endsection
