importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
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
//const app = initializeApp(firebaseConfig);
firebase.initializeApp(firebaseConfig);
// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const {title, body} = payload.notification;
    const notificationTitle = 'Background Message Title';
    const notificationOptions = {
      body,
    };

    self.registration.showNotification(notificationTitle,
      notificationOptions);
  });

