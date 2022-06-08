<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action={{url("/uploadImg")}} method="post" enctype="multipart/form-data">
    @method('POST')
    @csrf
    <input type="file" name="image">
    <button type="submit">submit</button>
</form>





    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
          apiKey: "AIzaSyDdoggNAdJ08JuC-Ms6CaNtuZ2IWM-cDxg",
          authDomain: "freelancerops.firebaseapp.com",
          projectId: "freelancerops",
          storageBucket: "freelancerops.appspot.com",
          messagingSenderId: "661837197172",
          appId: "1:661837197172:web:14284773ee4107fa330621",
          measurementId: "G-P9S7LLCT2J"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
      </script>
</body>
</html>
