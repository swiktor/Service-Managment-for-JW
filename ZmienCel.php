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
      <title>Zmień profil głosiciela</title>
<?php require "czesci/head";?>
   </head>
   <body>
      <?php require "czesci/navbar_glowny";?>
      <div class="table-responsive">
      <table class="table table-dark text-center">
         <form action="ZmienCel.php" method="post">
            <tr>
               <td >
                  <select name='id_celu' id="id_celu">
                     <?php
                        while ($ProfileLista = mysqli_fetch_array($WProfileLista)) {
                            if ($ProfileLista['id_celu'] == $id_celu) {
                                echo "<option selected='selected' value=".$ProfileLista['id_celu'].">".$ProfileLista['pelna_nazwa_celu']."</option>";
                            } else {
                                echo "<option value=".$ProfileLista['id_celu'].">".$ProfileLista['pelna_nazwa_celu']."</option>";
                            }
                        } ?>
                  </select>
               </td>
            </tr>
            <tr>
               <td>
                  <input type="hidden" name="editor" value="1">
                  <input type="hidden" name="id_uzytkownika" value="<?php echo $id_uzytkownika; ?>">
                  <input type="submit" name="" value="Gotowe">
               </td>
            </tr>
         </form>
      </div>
   </body>
</html>


<?php
if (isset($_POST['editor']) && $_POST['editor'] ==1) {
          $id_uzytkownika = $_POST['id_uzytkownika'];
          $id_celu = $_POST['id_celu'];

          require "ConnectToDB.php";
          $KwZmienCel = "UPDATE uzytkownicy SET id_celu = '$id_celu' where id_uzytkownika = '$id_uzytkownika';";
          $ZmienCel = mysqli_query($link, $KwZmienCel);

          if ($ZmienCel)
          {
            echo '<script language="javascript">';
            echo 'alert("Zmieniono profil głosiciela")';
            echo '</script>';

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
          header("refresh:0;url=Logowanie.php?skad=ZmienCel.php?id_celu=$id_celu");
      }

  ?>
