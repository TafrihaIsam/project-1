<?php
session_start();

// সব সেশন ভেরিয়েবল মুছে ফেলা
$_SESSION = array();

// সেশনটি পুরোপুরি ধ্বংস করা
session_destroy();

// লগইন পেজে পাঠিয়ে দেওয়া
header("Location: login.php");
exit();
?>