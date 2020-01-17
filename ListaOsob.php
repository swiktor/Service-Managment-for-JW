<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>

<?php
require "ConnectToDB.php";
    $kwerenda_osoby_lista = "call ListaOsobStatystyczna();";
    $wynik_osoby_lista=mysqli_query($link, $kwerenda_osoby_lista); ?>
 <!DOCTYPE html>
 <html lang="pl">
   <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta charset="utf-8">
     <title>Lista osób</title>
 <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   <body>
 <div id='tabelka_show' name='tabelka_show'>
 <table border=1>
<tr>
  <td colspan="7"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td>
</tr>
 <tr>
 <th>Lp.</th>
 <th>Nazwisko</th>
 <th>Imię</th>
<!-- <th>Id_osoby</th> -->
 <!-- <th>Ilość</th> -->
 <th>Info</th>
 <th>Umów</th>
 </tr>

 <?php
 $i =1;


    while ($komorka_show_osoby = mysqli_fetch_array($wynik_osoby_lista)) {
        echo "<tr>";
        echo "<td>".$i++."</td>";
        echo "<td>".$komorka_show_osoby['nazwisko']."</td>";
        echo "<td>".$komorka_show_osoby['imie']."</td>";
        // echo "<td>".$komorka_show_osoby['id_osoby']."</td>";
        // echo "<td>".$komorka_show_osoby['ile']."</td>";
        echo "<td><a color='black' href='InfoOsoba.php?id_osoby=".$komorka_show_osoby['id_osoby']."'>Info</a></font></td>";
        echo "<td><a href='UmowSluzbe.php?id_osoby=".$komorka_show_osoby['id_osoby']."'>Umów</td>";
    } ?>

   </div>

 </body>
 </html>

 <?php
} else {
     header("refresh:0;url=GAuth/Logowanie.php?skad=ListaOsob.php");
 }

  ?>
