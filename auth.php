<?php
  session_start();
  $_SESSION['TOTP']= $_COOKIE['SluzbyTOTP'];
  $_SESSION['id_uzytkownika'] = $_COOKIE['SluzbyID'];

$id_uzytkownika = $_SESSION['id_uzytkownika'];
