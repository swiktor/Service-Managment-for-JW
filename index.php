<?php
   require 'auth.php';
   
   if (isset($_SESSION['TOTP']) && $_SESSION['TOTP'] = 'JW')
   {?>

<?php
   require "ConnectToDB.php";
   $kwerenda_show_osoby = "call WyszukiwarkaSluzbyAll ($id_uzytkownika);";
   $wynik_show_osoby = mysqli_query($link, $kwerenda_show_osoby); ?>

<!DOCTYPE html>
<html lang="pl">

<head>
   <title>Lista służb</title>
   <?php require "czesci/head";?>
</head>

<body>
   <?php require "czesci/navbar_glowny";?>
   <div class="table-responsive">
      <table class="table table-dark text-center">
         <thead>
            <tr>
               <th scope="col">Lp.</th>
               <th scope="col">Imię i nazwisko</th>
               <th scope="col">Kiedy?</th>
               <th scope="col">Info</th>
               <th scope="col">Umów</th>
            </tr>
         </thead>
         <tbody>
         <?php
               $i = 1;
               while ($komorka_show_osoby = mysqli_fetch_array($wynik_show_osoby))
               {
                   echo "<tr>";
                   echo "<th scope='row'>" . $i++ . "</th>";

                   echo "<td>" . $komorka_show_osoby['kto'] . "</td>";
                   if ($komorka_show_osoby['roznica'] < 0 && $komorka_show_osoby['roznica'] > - 7)
                   {
                       echo "<td class='bg-success'>" . $komorka_show_osoby['kiedy_sluzba'] . "</td>";
                   }
                   elseif ($komorka_show_osoby['roznica'] <= - 7 && $komorka_show_osoby['roznica'] > - 14)
                   {
                       echo "<td class='bg-warning'>" . $komorka_show_osoby['kiedy_sluzba'] . "</td>";
                   }
                   elseif ($komorka_show_osoby['roznica'] <= - 14 && $komorka_show_osoby['roznica'] > - 30)
                   {
                       echo "<td class='bg-danger'>" . $komorka_show_osoby['kiedy_sluzba'] . "</td>";
                   }
                   elseif ($komorka_show_osoby['roznica'] <= - 30)
                   {
                       echo "<td class='bg-dark'>" . $komorka_show_osoby['kiedy_sluzba'] . "</td>";
                       $kontrolka = 1;
                   }
                   else
                   {
                       echo "<td class='bg-primary'>" . $komorka_show_osoby['kiedy_sluzba'] . "</td>";
                   }
                   echo "<td><a color='black' href='InfoOsoba.php?id_osoby=" . $komorka_show_osoby['id_osoby'] . "'>Info</a></td>";
                   echo "<td><font color='black'><a color='black' href='UmowSluzbe.php?id_osoby=" . $komorka_show_osoby['id_osoby'] . "&id_typu=" . $komorka_show_osoby['id_typu'] . "'>Umów</a></td>";
                   echo "</tr>";
               } ?>
   </tbody>   
   </table>
   </div>
</body>
</html>

<?php
   }
   else
   {
      header("refresh:0;url=Logowanie.php?skad=index.php");
   }?>