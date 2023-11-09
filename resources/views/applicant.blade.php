@extends('header')
@section('content')
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
    <center>
        <table>
            @if (count($cv) <= 0)
                <div class="card mb-3" style="width: 70%; margin-top: 20px">
                    <h1 class='card-title mt-3 mb-3'>Tidak Ada Pendaftar Untuk Saat Ini...</h1>
                </div>
            @else
                @foreach ($cv as $itemCV)
                    <tr>
                        <div class="card mb-3" style="width: 30rem; margin-top: 20px">
                            <div class="card-body">
                                @foreach ($applicantList as $applicant)
                                    @if ($applicant['cust_id'] == $itemCV['cust_id'])
                                        <h5 class="card-title">{{ $applicant['nama'] }}</h5>
                                        <p class="card-text">Contact: {{ $applicant['nomorhp'] }}</p>
                                        <hr>
                                        <div class="card-text">
                                            {{ $itemCV['applicant_desc'] }}
                                        </div>
                                        <hr>
                                        <a href={{url("/storage/cv/$itemCV[cv]")}} download class="btn btn-success">Lihat CV</a>
                                        <a href="{{ url("/loadProfilApplicant/c/$itemCV[cust_id]/$itemCV[applicant_id]/$modulId/$proyekId") }}"
                                            class="btn btn-primary">Lihat Profil</a>
                                        <button class="btn btn-primary"
                                            onclick="openForm('{{ $applicant['nama'] }}')">Chat</button>
                                        {{-- <a href="{{url("/previewcv/$itemCV[cv]")}}" class="btn btn-success">Terima</a>
                                        <a href="{{url("/loadProfilApplicant/c/$itemCV[cust_id]/$itemCV[applicant_id]/$modulId/$proyekId")}}" class="btn btn-danger">Tolak</a> --}}
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </tr>
                @endforeach
            @endif

        </table>
        @foreach ($cv as $itemCV)
            @foreach ($applicantList as $applicant)
                @if ($applicant['cust_id'] == $itemCV['cust_id'])
                    <div class="chat-popup {{ $applicant['nama'] }}" id="myForm">
                        <form action="{{ url("/kirimPesan") }}" method="post"
                            class="form-container">
                            @method('POST')
                            @csrf
                            <input type="hidden" name='reciever' value="{{$applicant['cust_id']}}_Applicant%20{{$judul->title}}">
                            <table>
                                <tr>
                                    <td style="width: 170%">
                                        <h1>Chat</h1>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger cancel center"
                                            onclick="closeForm('{{ $applicant['nama'] }}')">
                                            <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                                        </button>
                                    </td>
                                </tr>
                            </table>

                            <label for="formFile" class="form-label">Kirim Pesan Ke</label>
                            <p>Applicant{{ $applicant['nama'] }}</p>
                            <label for="desc" class="form-label mt-3">Pesan Anda</label>
                            <textarea class="form-control" aria-label="Deskripsikan dalam 1000 huruf" name="pesan" maxlength="1200" id='desc'
                                placeholder="Ketikan Pesan Disini"></textarea>


                            <button type="submit" class="btn bton btn-success"><i class="bi bi-send"></i></button>

                        </form>
                    </div>
                @endif
            @endforeach
        @endforeach


        <a href={{ url()->previous(); }} class="btn btn-secondary mt-3 mb-3">Kembali</a>
    </center>

    <script>
        function openForm(name) {
            document.getElementsByClassName(name)[0].style.display = "block";
        }

        function closeForm(name) {
            document.getElementsByClassName(name)[0].style.display = "none";
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
