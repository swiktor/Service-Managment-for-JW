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
        $kwerenda_spr_add = "UPDATE jw.sprawozdania SET publikacje='$publikacje',filmy='$filmy',odwiedziny='$odwiedziny',studia='$studia',godziny='$godziny' WHERE id_sprawozdania='$id_sprawozdania';";
        $wynik_spr_add = mysqli_query($link, $kwerenda_spr_add);

        if ($wynik_spr_add) {
            echo '<script language="javascript">';
            echo 'alert("Edytowano sprawozdanie")';
            echo '</script>';
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
    $kwerenda_sprawozdanie = "SELECT * FROM jw.sprawozdania where id_sprawozdania = $id_sprawozdania;";
    $wynik_sprawozdanie=mysqli_query($link, $kwerenda_sprawozdanie);
    $komorka_sprawozdanie = mysqli_fetch_array($wynik_sprawozdanie); ?>

    <!DOCTYPE html>
    <html lang="pl" dir="ltr">
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>Sprawozdania ze służby</title>
      </head>
      <body>
    <div id='tabelka_show' name='tabelka_show' border=1>
      <tr>
        <td colspan="8"><font color='black' style="font-weight:bold"><a color='black' href='Sprawozdania.php'>Wszystkie sprawozdania</a></font></td>
      </tr>
      <form action="DodajSprawozdanie.php" method="post">

  <div class=""><label>Publikacje:</label></div><input type="text" name="publikacje" placeholder = 'Publikacje' value="<?php echo $komorka_sprawozdanie['publikacje']; ?>">
  <br>
  <div class=""><label>Filmy:</label></div><input type="text" name="filmy" placeholder = 'Filmy' value="<?php echo $komorka_sprawozdanie['filmy']; ?>">
  <br>
  <div class=""><label>Odwiedziny:</label></div><input type="text" name="odwiedziny" placeholder = 'Odwiedziny' value="<?php echo $komorka_sprawozdanie['odwiedziny']; ?>">
  <br>
  <div class=""><label>Studia:</label></div><input type="text" name="studia" placeholder = 'Studia' value="<?php echo $komorka_sprawozdanie['studia']; ?>">
  <br>
  <div class=""><label>Godziny:</label></div><input type="time" name="godziny" value="<?php echo $komorka_sprawozdanie['godziny']; ?>">
  <input type="hidden" name="editor" value="1">
  <input type="hidden" name="id_sprawozdania" value="<?php echo $id_sprawozdania; ?>">
  <br>
  <div class=""><input type="submit" name="" value="Zapisz sprawozdanie"></div>

 <?php
} else {
        header("refresh:0;url=GAuth/Logowanie.php?skad=InfoSluzba.php?id_sluzby=$id_sluzby");
    }

  ?>
