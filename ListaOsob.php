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
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <title>Lista osób</title>
      <link rel="stylesheet" type="text/css" href="style.css">
      <script src="scripts.js"></script>
   </head>
   <body>
      <div id='tabelka_show' name='tabelka_show'>
         <table border=1>
            <tr>
               <td colspan="5">
                  <a color='black' href='index.php'>Strona główna</a>
               </td>
            </tr>
            <tr>
               <th>Lp.</th>
               <th>Nazwisko</th>
               <th>Imię</th>
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
                      echo "<td><a href='InfoOsoba.php?id_osoby=".$komorka_show_osoby['id_osoby']."'>Info</a></td>";
                      echo "<td><a href='UmowSluzbe.php?id_osoby=".$komorka_show_osoby['id_osoby']."'>Umów</td>";
                      echo "</tr>";
                  } ?>
            <tr>
               <form action="ListaOsob.php" method="post" onsubmit="return sprawdzenieFormularzaDodajOsobe()" name="dodajOsobe">
                  <td><?php echo $i++; ?></td>
                  <td><input type="text" size='10' id="nazwisko" name="nazwisko" placeholder='Nazwisko' value=""></td>
                  <td><input type="text" size='10' id="imie" name="imie" placeholder='Imię' value=""></td>
                  <input type="hidden" name="editor" value="1">
                  <td colspan="3"><input type="submit" value="Dodaj osobę"></td>
               </form>
            </tr>
         </table>
      </div>
   </body>
</html>

<?php
   } else {
           header("refresh:0;url=GAuth/Logowanie.php?skad=ListaOsob.php");
       }
   
     ?>
