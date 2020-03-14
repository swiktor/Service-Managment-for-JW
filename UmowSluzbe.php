<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>

<?php

if (isset($_GET['id_osoby'])) {
    $id_osoby = $_GET['id_osoby'];
}

    if (isset($_GET['id_typu'])) {
        $id_typu = $_GET['id_typu'];
    }
    require "ConnectToDB.php";
    $kwerenda_ListaOsobAktywnych = "call ListaOsobAktywnych;";
    $wynik_ListaOsobAktywnych=mysqli_query($link, $kwerenda_ListaOsobAktywnych);

    require "ConnectToDB.php";
    $kwerenda_ListaTypy = "call TypyLista;";
    $wynik_ListaTypy=mysqli_query($link, $kwerenda_ListaTypy); ?>


<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Umów służbę</title>
  </head>
  <body>
<div id='tabelka_show' name='tabelka_show' border=1>
  <tr>
    <td colspan="8"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td>
  </tr>
  <form action="UmowSluzbe.php" method="post">

    <select name='id_osoby' id="id_osoby">
      <option value="0">Osoba</option>
      <?php
      while ($komorka_ListaOsobAktywnych = mysqli_fetch_array($wynik_ListaOsobAktywnych)) {
          if ($komorka_ListaOsobAktywnych['id_osoby'] == $id_osoby) {
              echo "<option selected='selected' value=".$komorka_ListaOsobAktywnych['id_osoby'].">".$komorka_ListaOsobAktywnych['kto']."</option>";
          } else {
              echo "<option value=".$komorka_ListaOsobAktywnych['id_osoby'].">".$komorka_ListaOsobAktywnych['kto']."</option>";
          }
      } ?>
    </select>

<br>

    <select name='id_typu' id="id_typu">
      <option value="0">Typ</option>

      <?php
      while ($komorka_ListaTypy = mysqli_fetch_array($wynik_ListaTypy)) {
          if ($komorka_ListaTypy['id_typu'] == $id_typu) {
              echo "<option selected='selected' value=".$komorka_ListaTypy['id_typu'].">".$komorka_ListaTypy['typ_czas']."</option>";
          } else {
              echo "<option value=".$komorka_ListaTypy['id_typu'].">".$komorka_ListaTypy['typ_czas']."</option>";
          }
      } ?>
    </select>

    <br>
    <input type="datetime-local" id="kiedy_sluzba_od" name="kiedy_sluzba_od">
    <br>

    <script type="text/javascript">
    var tzoffset = (new Date()).getTimezoneOffset() * 60000; //offset in milliseconds
    var localISOTime = (new Date(Date.now() - tzoffset)).toISOString().slice(0,-1);
    var localISOTimeWithoutSeconds = localISOTime.slice(0,16);
    document.getElementById("kiedy_sluzba_od").value = localISOTimeWithoutSeconds;
    </script>

    <input type="hidden" name="editor" value="1">
    <input type="submit" name="" value="Gotowe">
  </form>
</div>
  </body>
</html>

<?php
$editor = $_POST['editor'];

    if (isset($_POST['id_osoby'])) {
        $id_osoby = $_POST['id_osoby'];
    }

    if (isset($_POST['id_typu'])) {
        $id_typu = $_POST['id_typu'];
    }

    if (isset($_POST['kiedy_sluzba_od'])) {
        $kiedy_sluzba_od = $_POST['kiedy_sluzba_od'];
    }

    if ($editor==1) {
        require "ConnectToDB.php";
        $kwerenda_dodaj_sluzbe = "CALL DodajNowaSluzbeFunkcja ($id_osoby, $id_typu, '$kiedy_sluzba_od', $id_uzytkownika);";
        $wynik_dodaj_sluzbe=mysqli_query($link, $kwerenda_dodaj_sluzbe);
        if ($wynik_dodaj_sluzbe) {
            require "ConnectToDB.php";
            $kwerenda_kalendarz = "CALL DaneDoKalendarza($id_osoby, $id_typu,'$kiedy_sluzba_od',$id_uzytkownika)";
            $wynik_kalendarz = mysqli_query($link, $kwerenda_kalendarz);
            $komorka_kalendarz = mysqli_fetch_array($wynik_kalendarz);

            $kto = $komorka_kalendarz['kto'];
            $nazwa_typu = $komorka_kalendarz['nazwa_typu'];
            $kiedy_sluzba_od = $komorka_kalendarz['kiedy_sluzba_od'];
            $czas_trwania = $komorka_kalendarz['czas_trwania'];
            $id_sluzby = $komorka_kalendarz['id_sluzby'];

            $kiedy_sluzba_do_sub  = substr($kiedy_sluzba_od, 0, 10);
            $kiedy_sluzba_do_diff =  date('H:i', (strtotime($kiedy_sluzba_od) + strtotime($czas_trwania)));
            $kiedy_sluzba_do =  $kiedy_sluzba_do_sub . 'T' .$kiedy_sluzba_do_diff . ":00";

            $kiedy_sluzba_od = substr_replace($kiedy_sluzba_od, "T", 10, 1)."+01:00";
            $kiedy_sluzba_do = $kiedy_sluzba_do."+01:00";

            require 'kalendarzsync.php';

            $event = new Google_Service_Calendar_Event(array(
        'summary' => $kto,
        'location' => '',
        'description' => $nazwa_typu,
        'start' => array(
          'dateTime' => $kiedy_sluzba_od,
          'timeZone' => 'Europe/Warsaw',
        ),
        'end' => array(
      'dateTime' => $kiedy_sluzba_do,
          'timeZone' => 'Europe/Warsaw',
        ),
        'recurrence' => array(),
        'attendees' => array(),
        'reminders' => array(
          'useDefault' => true,
          'overrides' => array(),
        ),
      ));

            $event = $service->events->insert($calendarId, $event);
            $event_id_gcal =  $event->id;
            require "ConnectToDB.php";
            $kwerenda_id_gcal = "UPDATE jw.sluzby SET id_gcal = '$event_id_gcal' where id_sluzby = $id_sluzby;";
            mysqli_query($link, $kwerenda_id_gcal);
            echo '<script language="javascript">';
            echo 'alert("Dodano służbę")';
            echo '</script>';

            $QueryAddLog="call LogAdd($id_uzytkownika,'Add ministry','$ip');";
            mysqli_query($link, $QueryAddLog);

            header("refresh:0;url=InfoOsoba.php?id_osoby=$id_osoby");
        } else {
            echo '<script language="javascript">';
            echo 'alert("Nie udało się dodać służby")';
            echo '</script>';
            header("refresh:0;url=InfoOsoba.php?id_osoby=$id_osoby");
        }
        header("refresh:0;url=InfoOsoba.php?id_osoby=$id_osoby");
    } ?>

<?php
} else {
        header("refresh:0;url=GAuth/Logowanie.php?skad=UmowSluzbe.php");
    }

 ?>
