// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getDatabase } from "firebase/database";
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
