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

if (!empty($_SERVER['HTTP_CLIENT_IP']))
{
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
{
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
    $ip = $_SERVER['REMOTE_ADDR'];
}

?>
