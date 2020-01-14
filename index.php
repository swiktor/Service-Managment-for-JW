<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>

<?php
include "ConnectToDB.php";
    $kwerenda_show_osoby = "call WyszukiwarkaSluzbyAll ($id_uzytkownika);";
    $wynik_show_osoby=mysqli_query($link, $kwerenda_show_osoby); ?>
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
   <!-- <tr>
     <td colspan="6"><font color='black' style="font-weight:bold"><a color='black' href='ListaKalendarz.php'>Lista z kalendarza</a></font></td>
 </tr> -->
 <tr>
     <td colspan="6"><font color='black' style="font-weight:bold"><a color='black' href='Sprawozdania.php'>Sprawozdania</a></font></td>
   </tr>
   <tr>
     <td colspan="3"><font color='black' style="font-weight:bold"><a color='black' href='ListaOsob.php'>Lista osób</a></font></td>
   <td colspan="3"><font color='black' style="font-weight:bold"><a color='black' href='UmowSluzbe.php'>Umów</a></font></td>
   </tr>

 <tr>
   <th>Lp.</th>
<th>Nazwisko</th>
 <th>Imię</th>
 <th>Kiedy?</th>
 <th>Info</th>
 <th>Umów</th>
 </tr>

 <?php
 $i =1;
    while ($komorka_show_osoby = mysqli_fetch_array($wynik_show_osoby)) {
        echo "<tr>";
        echo "<td>".$i++."</td>";
        echo "<td>".$komorka_show_osoby['nazwisko']."</td>";
        echo "<td>".$komorka_show_osoby['imie']."</td>";
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
        echo "<td><a color='black' href='InfoOsoba.php?id_osoby=".$komorka_show_osoby['id_osoby']."'>Info</a></font></td>";
        echo "<td><font color='black'><a color='black' href='UmowSluzbe.php?id_osoby=".$komorka_show_osoby['id_osoby']."&id_typu=".$komorka_show_osoby['id_typu']."'>Umów</a></font></td></tr>";
    } ?>


</table>
</div>
</body>
</html>

<?php
} else {
        header("refresh:0;url=GAuth/Logowanie.php?skad=index.php");
    }

 ?>
