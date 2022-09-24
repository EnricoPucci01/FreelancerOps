@extends('header')
@section('content')
    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
        }

        .chat {
            border: 1px solid gray;
            border-radius: 3px;
            width: 50%;
            padding: 0.5cm
        }

        .chat-left {
            background-color: white;
            align-self: flex-start;
        }

        .chat-right {
            background-color: #adff2f7f;
            align-self: flex-end;
        }
    </style>
    {{-- <link rel="stylesheet" href="<?php echo asset('chatboxCSS.css'); ?>" type="text/css"> --}}

    <div class="card bg-dark" style="height: 100%">
        <div class="menu bg-warning">
            {{-- <div class="back"><i class="fa fa-chevron-left"></i> <img src="https://i.imgur.com/DY6gND0.png"
                draggable="false" /></div> --}}
            <div class="name fw-bold"><span class="mr-3">Chat</div>
        </div>

        <div class="card-body">
            <div class="chat-container">
                @foreach ($chatList as $item)
                    @if ($item->sender_id == session()->get('cust_id'))
                        <p class="chat chat-right">
                            <b>Sender: {{ $item->sender_id }}</b>
                            {{ $item->message }}
                        </p>
                    @else
                        <p class="chat chat-left">
                            <b>Sender: {{ $item->sender_id }}</b>
                            {{ $item->message }}
                        </p>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="mt-3 mb-3" style="margin: auto;width: 50%;padding: 10px;">
            <form action={{ url('/createChat') }} method="POST">
                @csrf
                @method('POST')
                <table style="width: 100%">
                    <tr>
                        <td>
                            <input class="textarea bg-light form-control" type="text" name='message'
                                placeholder="Ketikan Pesan Anda Disini ..." />
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary ml-2 kirim">
                                <h2><i class="bi bi-send"></i></h2>
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    @endsection
    @section('script')
        <script>
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

            function retrieveToken(){
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

            messaging.onMessage((payload)=>{
                console.log('Message recieved');
                console.log(payload);

                location.reload();
            })
        </script>
    @endsection
