<?php
   require 'auth.php';
   
   if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
       ?>
<?php
   require "ConnectToDB.php";
   $miesiac = $_SESSION['miesiac'];
   $rok =  $_SESSION['rok'];
   
   $kwerenda_statystyka_osobowa = "call StatystykaOsobowa ($id_uzytkownika, $miesiac, $rok);";
   $wynik_statystyka_osobowa=mysqli_query($link, $kwerenda_statystyka_osobowa); ?>

<!DOCTYPE html>
<html lang="pl">

<head>
   <title>Statystyka osobowa</title>
   <?php require "czesci/head";?>
</head>

<body>
   <?php require "czesci/navbar_glowny";?>
   <div class="table-responsive">
      <table class="table table-dark text-center">
         <thead>
            <tr>
               <th scope="col">Lp.</th>
               <th scope="col">Kto</th>
               <th scope="col">Ilość wyruszeń</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i =1;
               while ($komorka_statystyka_osobowa = mysqli_fetch_array($wynik_statystyka_osobowa)) {
               echo "<tr>";
               echo "<th scope='row' class ='font-weight-bold'>" . $i++ . "</th>";
               echo "<td><a href='InfoOsoba.php?id_osoby=".$komorka_statystyka_osobowa['id_osoby']."'>".$komorka_statystyka_osobowa['kto']."</a></td>";
               echo "<td>".$komorka_statystyka_osobowa['ile']."</td>";
               echo "</tr>";
               } ?>
         </tbody>
      </table>
   </div>
</body>

</html>

<?php
   } else {
           header("refresh:0;url=Logowanie.php?skad=Sprawozdania.php");
       }?>