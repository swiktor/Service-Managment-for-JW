<?php
   require_once 'auth.php';
   
   if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
       ?>
<?php
require_once "ConnectToDB.php";
   if (isset($_POST['editor']) && $_POST['editor'] =='1') {
      $nazwisko = ucfirst(strtolower(mysqli_real_escape_string($link,$_POST['nazwisko'])));
      $imie = ucfirst(strtolower(mysqli_real_escape_string($link,$_POST['imie'])));
   
      $kwerenda_dodaj_osobe = "call DodajOsobe ('$nazwisko','$imie');";
      
      if(mysqli_query($link, $kwerenda_dodaj_osobe))
      {

        echo '<script language="javascript">';
        echo 'alert("Dodano nową osobę")';
        echo '</script>';
         
      }
      else {
        echo '<script language="javascript">';
        echo 'alert("Nie udało się dodać nowej osoby")';
        echo '</script>';
      

      }
   }
    $kwerenda_osoby_lista = "call ListaOsobStatystyczna();";
    $wynik_osoby_lista=mysqli_query($link, $kwerenda_osoby_lista); ?>

<!DOCTYPE html>
<html lang="pl">

<head>
   <title>Lista osób</title>
   <?php require "czesci/head";?>
</head>

<body>
   <?php require "czesci/navbar_glowny";?>


   <div class="table-responsive">
      <table class="table table-dark text-center">
         <thead>
            <tr>
               <th scope="col">Lp.</th>
               <th scope="col">Nazwisko</th>
               <th scope="col">Imię</th>
               <th scope="col">Info</th>
               <th scope="col">Umów</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i =1;
                  while ($komorka_show_osoby = mysqli_fetch_array($wynik_osoby_lista)) {
                      echo "<tr>";
                      echo "<td scope='row' class='font-weight-bold'>".$i++."</td>";
                      echo "<td>".$komorka_show_osoby['nazwisko']."</td>";
                      echo "<td>".$komorka_show_osoby['imie']."</td>";
                      echo "<td><a href='InfoOsoba.php?id_osoby=".$komorka_show_osoby['id_osoby']."'>Info</a></td>";
                      echo "<td><a href='UmowSluzbe.php?id_osoby=".$komorka_show_osoby['id_osoby']."'>Umów</td>";
                      echo "</tr>";
                  } ?>
            <tr>
               <form action="ListaOsob.php" method="post" onsubmit="return sprawdzenieFormularzaDodajOsobe()"
                  name="dodajOsobe">
                  <td><?php echo $i++; ?></td>
                  <td><input type="text" size='10' id="nazwisko" name="nazwisko" placeholder='Nazwisko' value=""></td>
                  <td><input type="text" size='10' id="imie" name="imie" placeholder='Imię' value=""></td>
                  <input type="hidden" name="editor" value="1">
                  <td colspan="3"><input type="submit" value="Dodaj osobę"></td>
               </form>
            </tr>
         </tbody>
      </table>
   </div>
</body>

</html>

<?php
   } else {
           header("refresh:0;url=Logowanie.php?skad=ListaOsob.php");
       }
   
     ?>