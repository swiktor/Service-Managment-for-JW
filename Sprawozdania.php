<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP'] = 'JW') {
    ?>

<?php
    if (isset($_POST['wyszukiwarka']) && $_POST['wyszukiwarka'] == '1') {
        session_start();
        $_SESSION['miesiac'] = $_POST['miesiac'];
        $_SESSION['rok'] = $_POST['rok'];
    }

    if (isset($_POST['wyszukiwarka']) && $_POST['wyszukiwarka'] == '0') {
        session_start();
        $_SESSION['miesiac'] = date('m');
        $_SESSION['rok'] = date('Y');
    }

    session_start();
    $miesiac = $_SESSION['miesiac'];
    $rok = $_SESSION['rok'];

    if (isset($_POST['editor']) && $_POST['editor'] == '1') {
        $id_sprawozdania = _POST['id_sprawozdania'];
        $publikacje = $_POST['publikacje'];
        $filmy = $_POST['filmy'];
        $odwiedziny = $_POST['odwiedziny'];
        $studia = $_POST['studia'];
        $godziny = $_POST['godziny'];
    }

    require "ConnectToDB.php";
    $kwerenda_show_sprawozdania = "call SprawozdaniaLista ($id_uzytkownika, $miesiac, $rok);";
    $wynik_show_sprawozdania = mysqli_query($link, $kwerenda_show_sprawozdania);

    require "ConnectToDB.php";
    $kwerenda_suma = "call SprawozdaniaSuma($id_uzytkownika, $miesiac, $rok)";
    $wynik_kwerenda_suma = mysqli_query($link, $kwerenda_suma);
    $komorka_kwerenda_suma = mysqli_fetch_array($wynik_kwerenda_suma); ?>


<!DOCTYPE html>
<html lang="pl">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Sprawozdania</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="scripts.js"></script>
</head>

<body>
  <div id='tabelka_show' name='tabelka_show'>
    <table border=1>

      <tr>
        <td colspan="3">
          <font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font>
        </td>
        <td colspan='5'><b>Profil: <a class ="podpowiedz" href='ZmienCel.php?id_uzytkownika=<?php echo $komorka_kwerenda_suma['id_uzytkownika']; ?>&id_celu=<?php echo $komorka_kwerenda_suma['id_celu']; ?>'><?php echo $komorka_kwerenda_suma['pelna_nazwa_celu']; ?>
            <span>Kliknij, by zmienić</span></a></b></td>
        <td colspan='3'><b><a color='black' href='BilansPioniera.php'>Bilans pioniera</a></b></td>
      </tr>
      <tr>
        <td colspan='2'><b><a color='black' href='StatystykaOsobowa.php'>Statystyka osobowa</a></b></td>
        <form class="" action="Sprawozdania.php" method="post">
          <td colspan="2">
            <font color='black' style="font-weight:bold">Wyszukiwarka</font>
          </td>
          <td colspan="2">
            <select name='miesiac' id="miesiac">
              <?php
    for ($i = 1;$i < 13;$i++) {
        if ($i == $miesiac) {
            $t = "selected='selected'";
        } else {
            $t = '';
        }

        switch ($i) {
            case '1':
                echo '<option ' . $t . ' value=' . $i . '>Styczeń</option>';
            break;

            case '2':
                echo '<option ' . $t . ' value=' . $i . '>Luty</option>';
            break;

            case '3':
                echo '<option ' . $t . ' value=' . $i . '>Marzec</option>';
            break;

            case '4':
                echo '<option  ' . $t . ' value=' . $i . '>Kwiecień</option>';
            break;

            case '5':
                echo '<option ' . $t . ' value=' . $i . '>Maj</option>';
            break;

            case '6':
                echo '<option ' . $t . ' value=' . $i . '>Czerwiec</option>';
            break;

            case '7':
                echo '<option ' . $t . ' value=' . $i . '>Lipiec</option>';
            break;

            case '8':
                echo '<option ' . $t . ' value=' . $i . '>Sierpień</option>';
            break;

            case '9':
                echo '<option ' . $t . ' value=' . $i . '>Wrzesień</option>';
            break;

            case '10':
                echo '<option ' . $t . ' value=' . $i . '>Pażdziernik</option>';
            break;

            case '11':
                echo '<option ' . $t . ' value=' . $i . '>Listopad</option>';
            break;

            case '12':
                echo '<option ' . $t . ' value=' . $i . '>Grudzień</option>';
            break;

            default:
                // code...
                
            break;
        }
    } ?>

              <option value="0">Miesiąc</option>
            </select>
          </td>
          <td colspan="2">
            <select name='rok' id="rok">

              <?php
    for ($i = 2019;$i < 2029;$i++) {
        if ($i == $rok) {
            echo "<option selected='selected' value='$i'>$i</option>";
        } else {
            echo "<option value='$i'>$i</option>";
        }
    } ?>
            </select>
          </td>

          <?php
    $m = date('m');
    $r = date('Y');

    if ($miesiac < 10) {
        $miesiac = '0' . $miesiac;
    }

    if ($miesiac != $m || $rok != $r) {
        echo '<input type="hidden" name="wyszukiwarka" value="0">';
    } else {
        echo '<input type="hidden" name="wyszukiwarka" value="1">';
    } ?>

          <td colspan="3"><input type="submit" value="Wyszukaj / Reset"></td>
        </form>
      </tr>

      <tr>
        <th>Lp.</th>
        <th>Kto</th>
        <th>Kiedy</th>
        <th>Typ</th>
        <th>Publikacje</th>
        <th>Filmy</th>
        <th>Odwiedziny</th>
        <th>Studia</th>
        <th>Godziny</th>
        <th>Edycja</th>
        <th>Bilans czasu</th>
      </tr>

      <?php
    $i = 1;
    while ($komorka_show_sprawozdania = mysqli_fetch_array($wynik_show_sprawozdania)) {
        echo "<tr>";
        echo "<form action='Sprawozdania.php' method='post'>";
        echo "<td>" . $i++ . "</td>";
        echo "<td><a color='black' href='InfoOsoba.php?id_osoby=" . $komorka_show_sprawozdania['id_osoby'] . "'>" . $komorka_show_sprawozdania['kto'] . "</a></font></td>";
        echo "<td>" . $komorka_show_sprawozdania['kiedy_sluzba'] . "</td>";
        echo "<td>" . $komorka_show_sprawozdania['nazwa_typu'] . "</td>";
        echo "<td>" . $komorka_show_sprawozdania['publikacje'] . "</td>";
        echo "<td>" . $komorka_show_sprawozdania['filmy'] . "</td>";
        echo "<td>" . $komorka_show_sprawozdania['odwiedziny'] . "</td>";
        echo "<td>" . $komorka_show_sprawozdania['studia'] . "</td>";
        echo "<td>" . $komorka_show_sprawozdania['godziny'] . "</td>";
        echo "<td><a color='black' href='DodajSprawozdanie.php?id_sprawozdania=" . $komorka_show_sprawozdania['id_sprawozdania'] . "'>Edytuj</a></font></td>";

        if ($komorka_show_sprawozdania['bilans_oczekiwania_rzeczywistosc'] >= '00:00') {
            echo "<td bgcolor='#90EE90'><b>" . $komorka_show_sprawozdania['bilans_oczekiwania_rzeczywistosc'] . "</b></td>";
        } else {
            echo "<td bgcolor='#ffcccb'><b>" . $komorka_show_sprawozdania['bilans_oczekiwania_rzeczywistosc'] . "</b></td>";
        }
        echo "</form>";
        echo "</tr>";
    }

    echo "<tr>";
    echo "<td colspan='4'><b>SUMA</td>";
    echo "<td><b>" . $komorka_kwerenda_suma['s_publikacje'] . "</b</td>";
    echo "<td><b>" . $komorka_kwerenda_suma['s_filmy'] . "</b</td>";
    echo "<td><b>" . $komorka_kwerenda_suma['s_odwiedziny'] . "</b</td>";
    echo "<td><b>" . $komorka_kwerenda_suma['s_studia'] . "</b</td>";
    echo "<td colspan='2'><b>" . $komorka_kwerenda_suma['s_godziny'] . "</b></td>";

    require "ConnectToDB.php";
    $KwBilansPionieraSuma = "call BilansPionieraSuma ($id_uzytkownika, $miesiac, $rok);";
    $BilansPionieraSuma = mysqli_query($link, $KwBilansPionieraSuma);
    $bilans_typow = mysqli_fetch_array($BilansPionieraSuma);
    if ($bilans_typow['bilans_typow'] >= '00:00') {
        echo "<td colspan='1' bgcolor='#90EE90'><b>" . $bilans_typow['bilans_typow'] . "</b></td>";
    } else {
        echo "<td colspan='1' bgcolor='#ffcccb'><b>" . $bilans_typow['bilans_typow'] . "</b></td>";
    }
    echo "</tr>"; ?>
      <tr>
        <td colspan='8'><b>Miesięczny bilans godzin</b></td>
        <?php if ($komorka_kwerenda_suma['roznica_godzin'] >= 0) {
        echo "<td colspan='3' bgcolor='#90EE90'><b>" . $komorka_kwerenda_suma['roznica_godzin'] . "</b></td>";
    } else {
        echo "<td colspan='3' bgcolor='#ffcccb'><b>" . $komorka_kwerenda_suma['roznica_godzin'] . "</b></td>";
    } ?>
      </tr>

      <tr>
        <td colspan='8'><b>Suma służby z typów</b></td>
        <?php if ($komorka_kwerenda_suma['bilans_stypy_kwantum'] >= '00:00') {
        echo "<td colspan='3' bgcolor='#90EE90'><b>" . $komorka_kwerenda_suma['s_typy'] . "</b></td>";
    } else {
        echo "<td colspan='3' bgcolor='#ffcccb'><b>" . $komorka_kwerenda_suma['s_typy'] . "</b></td>";
    } ?>
      </tr>




      <tr>
        <td colspan='8'><b>Nadmiar / niedobór godzin na dzień <?php echo date("d-m-Y"); ?></b></td>
        <?php
    $bilans_rzeczywisty = $komorka_kwerenda_suma['bilans_rzeczywisty'];
    if ($bilans_rzeczywisty >= '00:00') {
        echo "<td colspan='3' bgcolor='#90EE90'><b>" . $komorka_kwerenda_suma['bilans_rzeczywisty'] . "</b></td>";
    } else {
        echo "<td colspan='3' bgcolor='#ffcccb'><b>" . $komorka_kwerenda_suma['bilans_rzeczywisty'] . "</b></td>";
    } ?>
      </tr>

      <tr>
        <td colspan='8'><b>Cel dzienny na dzień <?php echo date("d-m-Y"); ?></b></td>
        <?php
    $rzeczywisty_cel_dzienny = $komorka_kwerenda_suma['rzeczywisty_cel_dzienny'];
    if ($rzeczywisty_cel_dzienny >= '00:00') {
        echo "<td colspan='3' bgcolor='#ffcccb'><b>" . $komorka_kwerenda_suma['rzeczywisty_cel_dzienny'] . "</b></td>";
    } else {
        echo "<td colspan='3' bgcolor='#90EE90'><b>00:00</b></td>";
    } ?>

      </tr>

    </table>
  </div>
</body>

</html>

<?php
} else {
    header("refresh:0;url=GAuth/Logowanie.php?skad=Sprawozdania.php");
}

?>
