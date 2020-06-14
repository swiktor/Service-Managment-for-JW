<?php
   require 'auth.php';
   
   if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
       ?>
<?php
   require "ConnectToDB.php";
   session_start();
   $miesiac = $_SESSION['miesiac'];
   $rok =  $_SESSION['rok'];
   
   $kwerenda_statystyka_osobowa = "call StatystykaOsobowa ($id_uzytkownika, $miesiac, $rok);";
   $wynik_statystyka_osobowa=mysqli_query($link, $kwerenda_statystyka_osobowa); ?>

<!DOCTYPE html>
<html lang="pl">
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <title>Statystyka osobowa</title>
      <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   <body>
      <div id='tabelka_show' name='tabelka_show'>
         <table border=1>
            <tr>
               <td colspan="2"><a href='index.php'>Strona główna</a></td>
               <td colspan="1"><a href='Sprawozdania.php'>Sprawozdania</a></td>
            </tr>
            <tr>
               <th>Lp.</th>
               <th>Kto</th>
               <th>Ilość wyruszeń</th>
            </tr>
            <?php
               $i =1;
               while ($komorka_statystyka_osobowa = mysqli_fetch_array($wynik_statystyka_osobowa)) {
               echo "<tr>";
               echo "<td>".$i++."</td>";
               echo "<td><a href='InfoOsoba.php?id_osoby=".$komorka_statystyka_osobowa['id_osoby']."'>".$komorka_statystyka_osobowa['kto']."</a></td>";
               echo "<td>".$komorka_statystyka_osobowa['ile']."</td>";
               echo "</tr>";
               } ?>
         </table>
      </div>
   </body>
</html>

<?php
   } else {
           header("refresh:0;url=GAuth/Logowanie.php?skad=Sprawozdania.php");
       }?>
