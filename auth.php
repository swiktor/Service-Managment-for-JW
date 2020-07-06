<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_GET['wyloguj']) && $_GET['wyloguj']=='1')
{
    setcookie("SluzbyTOTP", "", 1);
    setcookie("SluzbyID", "", 1);
    session_start();
    session_destroy();
    header("refresh:0;url=Logowanie.php");
}

else
{

   if (session_status() == PHP_SESSION_NONE) {
       session_cache_limiter('');
       session_start();
   }
   
   if (isset($_COOKIE['SluzbyTOTP']))
   {
       $_SESSION['TOTP'] = $_COOKIE['SluzbyTOTP'];
   }
   
   if (isset($_COOKIE['SluzbyID']))
   {
       $id_uzytkownika = $_COOKIE['SluzbyID'];
   }

}
?>