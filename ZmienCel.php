<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>


<?php
if (isset($_GET['id_celu'])) {
        $id_celu = $_GET['id_celu'];
        $id_uzytkownika = $_GET['id_uzytkownika'];


        require "ConnectToDB.php";
        $KwProfileLista = "call ProfileLista;";
        $WProfileLista=mysqli_query($link, $KwProfileLista);
    } ?>
 <!DOCTYPE html>
 <html lang="pl" dir="ltr">
   <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta charset="utf-8">
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>Zmień profil głosiciela</title>
   </head>
   <body>
 <div id='tabelka_show' name='tabelka_show' border=1>
   <tr>
     <td colspan="8"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td>
   </tr>
   <br>
   <tr>
     <td colspan="8"><font color='black' style="font-weight:bold"><a color='black' href='Sprawozdania.php'>Sprawozdania</a></font></td>
   </tr>
  <form action="ZmienCel.php" method="post">
    <select name='id_celu' id="id_celu">
      <option value="0">Cele</option>
      <?php
      while ($ProfileLista = mysqli_fetch_array($WProfileLista)) {
          if ($ProfileLista['id_celu'] == $id_celu) {
              echo "<option selected='selected' value=".$ProfileLista['id_celu'].">".$ProfileLista['pelna_nazwa_celu']."</option>";
          } else {
              echo "<option value=".$ProfileLista['id_celu'].">".$ProfileLista['pelna_nazwa_celu']."</option>";
          }
      } ?>
    </select>
    <input type="hidden" name="editor" value="1">
    <input type="hidden" name="id_uzytkownika" value="<?php echo $id_uzytkownika; ?>">
<br>
    <input type="submit" name="" value="Gotowe">


 </form>
</div>
 </body>
</html>


<?php
if (isset($_POST['editor']) && $_POST['editor'] ==1) {
          $id_uzytkownika = $_POST['id_uzytkownika'];
          $id_celu = $_POST['id_celu'];

          require "ConnectToDB.php";
          $KwZmienCel = "UPDATE jw.uzytkownicy SET id_celu = '$id_celu' where id_uzytkownika = '$id_uzytkownika';";
          $ZmienCel = mysqli_query($link, $KwZmienCel);

          if ($ZmienCel)
          {
            echo '<script language="javascript">';
            echo 'alert("Zmieniono profil głosiciela")';
            echo '</script>';

            require "ConnectToDB.php";
            $QueryAddLog="call LogAdd($id_uzytkownika,'Change profile','$ip');";
            mysqli_query($link, $QueryAddLog);
            header("refresh:0;url=Sprawozdania.php");

          }
          else {
            echo '<script language="javascript">';
            echo 'alert("Nie udało się zmienić profilu głosiciela")';
            echo '</script>';
            header("refresh:0;url=Sprawozdania.php");
          }

      } ?>





 <?php
} else {
          header("refresh:0;url=GAuth/Logowanie.php?skad=ZmienCel.php?id_celu=$id_celu");
      }

  ?>
