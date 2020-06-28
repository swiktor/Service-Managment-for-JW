<?php
   require 'auth.php';
   
   if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
       ?>

<?php
   require "ConnectToDB.php";   
   $kwerenda_CiCoNigdy = "call CiCoNigdy ($id_uzytkownika);";
   $wynik_CiCoNigdy=mysqli_query($link, $kwerenda_CiCoNigdy); ?>




<!DOCTYPE html>
<html lang="pl">

<head>
   <title>Ci co nigdy</title>
   <?php require "czesci/head";?>
</head>

<body>
   <?php require "czesci/navbar_glowny";?>
   <div class="table-responsive">
      <table class="table table-dark text-center">
         <thead>
            <tr>
               <th>Lp.</th>
               <th>Kto</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i =1;
               while ($komorka_CiCoNigdy = mysqli_fetch_array($wynik_CiCoNigdy)) {
               echo "<tr>";
               echo "<td><b>" . $i++ . "</b></td>";
               echo "<td><a href='InfoOsoba.php?id_osoby=".$komorka_CiCoNigdy['id_osoby']."'>".$komorka_CiCoNigdy['kto']."</a></td>";
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