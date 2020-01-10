<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>

<?php

if (isset($_GET['remove'])) {
    $remove = $_GET['remove'];


    if ($remove=1) {
        if (isset($_GET['id_sluzby'])) {
            $id_sluzby = $_GET['id_sluzby'];
        }

        if (isset($_GET['id_osoby'])) {
            $id_osoby = $_GET['id_osoby'];
        }


        include "ConnectToDB.php";
        $kwerenda_usuwanie = "DELETE FROM sluzby WHERE sluzby.id_sluzby=$id_sluzby";
        $wynik_usuwanie=mysqli_query($link, $kwerenda_usuwanie);

        if ($wynik_usuwanie) {
            echo '<script language="javascript">';
            echo 'alert("Usunięto służbę")';
            echo '</script>';
            header("refresh:0;url=InfoOsoba.php?id_osoby=$id_osoby");
        }

        if (!$wynik_usuwanie) {
            echo '<script language="javascript">';
            echo 'alert("Nie udało się usunąć służby")';
            echo '</script>';
            header("refresh:0;url=InfoOsoba.php?id_osoby=$id_osoby");
        }
    }
}




    if (isset($_GET['id_osoby'])) {
        $id_osoby = $_GET['id_osoby'];
    }

    include "ConnectToDB.php";
    $kwerenda_wyszukiwarka="call WyszukiwarkaOsobowa ($id_osoby, $id_uzytkownika)";
    $wynik_wyszukiwarka=mysqli_query($link, $kwerenda_wyszukiwarka); ?>

 <!DOCTYPE html>
 <html lang="pl">
   <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta charset="utf-8">
     <title>Służby</title>
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
  <!-- <th>ID</th> -->
 <th>Nazwisko</th>
 <th>Imię</th>
 <th>Typ</th>
 <th>Kiedy?</th>
 <th>Umów</th>
  <th>Usuń</th>
 </tr>

 <?php
 $i =1;

    while ($komorka_show_osoby = mysqli_fetch_array($wynik_wyszukiwarka)) {
        echo "<tr>";
        echo "<td>".$i++."</td>";
        // echo "<td>".$komorka_show_osoby['id_sluzby']."</td>";
        echo "<td>".$komorka_show_osoby['nazwisko']."</td>";
        echo "<td>".$komorka_show_osoby['imie']."</td>";
        echo "<td>".$komorka_show_osoby['nazwa_typu']."</td>";
        if ($komorka_show_osoby['roznica']<0 && $komorka_show_osoby['roznica']>-7) {
            echo "<td bgcolor='#90EE90'>".$komorka_show_osoby['kiedy_sluzba']."</td>";
        } elseif ($komorka_show_osoby['roznica']<=-7 && $komorka_show_osoby['roznica']>-14) {
            echo "<td bgcolor='#FFFFE0'>".$komorka_show_osoby['kiedy_sluzba']."</td>";
        } elseif ($komorka_show_osoby['roznica']<=-14 && $komorka_show_osoby['roznica']>-30) {
            echo "<td bgcolor='#ffcccb'>".$komorka_show_osoby['kiedy_sluzba']."</td>";
        } elseif ($komorka_show_osoby['roznica']<=-30) {
            echo "<td bgcolor='#d3d3d3'>".$komorka_show_osoby['kiedy_sluzba']."</td>";
            $kontrolka = 1;
        } else {
            echo "<td bgcolor='#add8e6'>".$komorka_show_osoby['kiedy_sluzba']."</td>";
        }
        echo "<td><font color='black'><a color='black' href='UmowSluzbe.php?id_osoby=".$komorka_show_osoby['id_osoby']."&id_typu=".$komorka_show_osoby['id_typu']."'>Umów</a></font></td>";
        echo "<td><font color='black'><a color='black' href='InfoOsoba.php?id_sluzby=".$komorka_show_osoby['id_sluzby']."&remove=1&id_osoby=".$komorka_show_osoby['id_osoby']."'>Usuń</a></font></td>";
    } ?>
 </body>
 </html>


 <?php
} else {
     header("refresh:0;url=GAuth/Logowanie.php?skad=InfoOsoba.php");
 }

  ?>
