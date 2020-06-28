<?php
   if (session_status() == PHP_SESSION_NONE) {
       session_cache_limiter('');
       session_start();
   }
   
   if (isset($_SESSION['TOTP']))
   {
       $_SESSION['TOTP'] = $_COOKIE['SluzbyTOTP'];
   }
   
   if (isset($_SESSION['id_uzytkownika']))
   {
       $id_uzytkownika = $_COOKIE['SluzbyID'];
   }

   ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>