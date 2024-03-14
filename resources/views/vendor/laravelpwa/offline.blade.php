<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <style tyle="text/css">
        body {
            width: 100%;
            min-height: 100vh;
            display: relative;
            margin: 0;
            padding: 0;
            background: -webkit-linear-gradient(-45deg, #183850 0, #183850 25%, #192C46 50%, #22254C 75%, #22254C 100%);
        }

        .wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            align-items: center;
            justify-content: center;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            text-align: center;
        }

        h1 {
            color: #e7880d;
            font-family: arial;
            font-weight: bold;
            font-size: 50px;
            letter-spacing: 5px;
            line-height: 1rem;
            text-shadow: 0 0 3px #e7880d;
        }

        h4 {
            color: #f1f1f1;
            font-family: arial;
            font-weight: 300;
            font-size: 16px;
        }
    </style>


    <title>FreelancerOPS</title>

    <script>
        (function() {
            navigator.serviceWorker.ready.then(function(sw) {
                    return sw.sync.register('offlineSync');
                });
            // window.onload = function() {

            // }
            const channel = new BroadcastChannel('sw-messages');
            channel.addEventListener('message', event => {
                if(event.data.title == "online"){

                    console.log('Received', event.data);
                    location.reload();
                }
            });
        })();
    </script>
</head>

<body>
    <div class="wrapper">
        <img src="{{ URL::to('images/LogoTA.png') }}" alt="FreelancerOPS Web Logo" width="350"
        height="80">

        <h1 id="status">OFFLINE</h1>
        <h4 id="message" style="font-weight: bold">Anda sedang tidak terhubung pada internet. Silahkan tunggu hingga
            anda terhubung kembali pada internet dan web akan memuat ulang secara otomatis</h4>

    </div>
</body>

</html>
