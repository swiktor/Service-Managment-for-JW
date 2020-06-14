<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>

<?php
if (isset($_GET['id_sprawozdania'])) {
        $id_sprawozdania = $_GET['id_sprawozdania'];
    }

    if (isset($_POST['id_sprawozdania'])) {
        $id_sprawozdania = $_POST['id_sprawozdania'];
    }

    if (isset($_POST['editor']) && $_POST['editor'] =='1') {
        $publikacje = $_POST['publikacje'];
        $filmy = $_POST['filmy'];
        $odwiedziny = $_POST['odwiedziny'];
        $studia = $_POST['studia'];
        $godziny  = $_POST['godziny'];

        require "ConnectToDB.php";
        $kwerenda_spr_add = "UPDATE sprawozdania SET publikacje='$publikacje',filmy='$filmy',odwiedziny='$odwiedziny',studia='$studia',godziny='$godziny' WHERE id_sprawozdania='$id_sprawozdania';";
        $wynik_spr_add = mysqli_query($link, $kwerenda_spr_add);

        if ($wynik_spr_add) {
            echo '<script language="javascript">';
            echo 'alert("Edytowano sprawozdanie")';
            echo '</script>';

            require "ConnectToDB.php";
            $QueryAddLog="call LogAdd($id_uzytkownika,'Edytowano sprawozdanie','$ip');";
            mysqli_query($link, $QueryAddLog);
            header("refresh:0;url=Sprawozdania.php");
        }

        if (!$wynik_spr_add) {
            echo '<script language="javascript">';
            echo 'alert("Nie udało się edytować sprawozdania")';
            echo '</script>';
            header("refresh:0;url=Sprawozdania.php");
        }
    }

    require "ConnectToDB.php";
    $kwerenda_sprawozdanie = "SELECT * FROM sprawozdania where id_sprawozdania = $id_sprawozdania;";
    $wynik_sprawozdanie=mysqli_query($link, $kwerenda_sprawozdanie);
    $komorka_sprawozdanie = mysqli_fetch_array($wynik_sprawozdanie); ?>

<!DOCTYPE html>
<html lang="pl" dir="ltr">
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <link rel="stylesheet" type="text/css" href="style.css">
      <title>Sprawozdania ze służby</title>
      <script src="scripts.js"></script>
   </head>
   <body>
      <div id='tabelka_show' name='tabelka_show'>
         <table border=1>
            <tr>
               <td><a color='black' href='Sprawozdania.php'>Wszystkie sprawozdania</a></td>
            </tr>
            <form action="DodajSprawozdanie.php" method="post">
            <tr>
               <td>
                  <div><label for="publikacje">Publikacje:</label></div>
                  <input type="number" id="publikacje" onkeyup="czyLiczba('publikacje')" name="publikacje" placeholder = 'Publikacje' value="<?php echo $komorka_sprawozdanie['publikacje']; ?>">
               </td>
            </tr>
            <tr>
               <td>
                  <div><label for="filmy">Filmy:</label></div>
                  <input type="number" id="filmy" onkeyup="czyLiczba('filmy')" name="filmy" placeholder = 'Filmy' value="<?php echo $komorka_sprawozdanie['filmy']; ?>">
               </td>
            </tr>
            <tr>
               <td>
                  <div><label for="odwiedziny">Odwiedziny:</label></div>
                  <input type="number" id="odwiedziny" onkeyup="czyLiczba('odwiedziny')" name="odwiedziny" placeholder = 'Odwiedziny' value="<?php echo $komorka_sprawozdanie['odwiedziny']; ?>">
               </td>
            </tr>
            <tr>
               <td>
                  <div><label for="studia">Studia:</label></div>
                  <input type="number" id="studia" onkeyup="czyLiczba('studia')" name="studia" placeholder = 'Studia' value="<?php echo $komorka_sprawozdanie['studia']; ?>">
               </td>
            </tr>
            <tr>
               <td>
                  <div><label for="godziny">Godziny:</label></div>
                  <input type="time" name="godziny" value="<?php echo $komorka_sprawozdanie['godziny']; ?>">
            <tr>
               <td>
                  <input type="hidden" name="editor" value="1">
                  <input type="hidden" name="id_sprawozdania" value="<?php echo $id_sprawozdania; ?>">
                  <div ><input type="submit" id="zapiszSprawozdanie"name="" value="Zapisz sprawozdanie"></div>
               </td>
            </tr>
         </table>
      </div>
   </body>

 <?php
} else {
        header("refresh:0;url=GAuth/Logowanie.php?skad=InfoSluzba.php?id_sluzby=$id_sluzby");
    }?>
