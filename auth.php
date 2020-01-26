<?php
  session_start();
  $_SESSION['TOTP']= $_COOKIE['SluzbyTOTP'];
  $_SESSION['id_uzytkownika'] = $_COOKIE['SluzbyID'];

$id_uzytkownika = $_SESSION['id_uzytkownika'];

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
