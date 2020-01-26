<?php
require 'auth.php';
if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {?>

<?php
require "ConnectToDB.php";
$kwerenda_ListaOsobAktywnych = "call ListaOsobAktywnych;";
$wynik_ListaOsobAktywnych=mysqli_query($link, $kwerenda_ListaOsobAktywnych);

require "ConnectToDB.php";
$QueryGetProfilesList="SELECT id_celu, nazwa FROM jw.cele;";
$GetProfilesList = mysqli_query($link, $QueryGetProfilesList);

if (isset($_GET['id_osoby']))
{
    $id_osoby = $_GET['id_osoby'];









 ?>
 <!DOCTYPE html>
 <html lang="pl" dir="ltr">
   <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta charset="utf-8">
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>Umów służbę</title>
   </head>
   <body>
 <div id='tabelka_show' name='tabelka_show' border=1>
   <form action="UmowSluzbe.php" method="post">
 <select name='id_osoby' id="id_osoby">
   <option value="0">Osoba</option>
   <?php
   while ($komorka_ListaOsobAktywnych = mysqli_fetch_array($wynik_ListaOsobAktywnych)) {
       if ($komorka_ListaOsobAktywnych['id_osoby'] == $id_osoby) {
           echo "<option selected='selected' value=".$komorka_ListaOsobAktywnych['id_osoby'].">".$komorka_ListaOsobAktywnych['kto']."</option>";
       } else {
           echo "<option value=".$komorka_ListaOsobAktywnych['id_osoby'].">".$komorka_ListaOsobAktywnych['kto']."</option>";
       }
   } ?>
 </select>

<br>

 <select name='id_celu' id="id_celu">
   <option value="0">Cele</option>
   <?php
   while ($ProfilesList = mysqli_fetch_array($GetProfilesList)) {
       if ($ProfilesList['id_celu'] == $id_celu) {
           echo "<option selected='selected' value=".$ProfilesList['id_celu'].">".$ProfilesList['nazwa']."</option>";
       } else {
           echo "<option value=".$ProfilesList['id_celu'].">".$ProfilesList['nazwa']."</option>";
       }
   } ?>
 </select>

 <br>

 <input type="password" name="password1" placeholder="Hasło">
<br>
 <input type="password" name="password2" placeholder="Powtórz hasło">
<br>
<input type="text" name="GCalendar" placeholder="GCalendar">
<br>
<input type="text" name="telegram_chat_id" placeholder="Telegram">
<br>
<input type="hidden" name="adder" value="2">
<input type="submit" name="" value="Dodaj użytkownika">




  </form>
</div>
  </body>
</html>





 <?php
} else {
       header("refresh:0;url=GAuth/Logowanie.php?skad=index.php");
   }  ?>
