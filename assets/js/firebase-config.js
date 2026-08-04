// Firebase Configuration for Ceylon Tours
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-analytics.js";

const firebaseConfig = {
  apiKey: "AIzaSyDmoD62jL35kB8WTdNXl00j4ucWI1Y-LyM",
  authDomain: "ceylon-tour.firebaseapp.com",
  projectId: "ceylon-tour",
  storageBucket: "ceylon-tour.firebasestorage.app",
  messagingSenderId: "1092642387783",
  appId: "1:1092642387783:web:2292d336186744a55efe62",
  measurementId: "G-LRYBVN57W1"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
console.log("Firebase initialized for Ceylon Tours!");
