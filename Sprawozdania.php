<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP'] = 'JW')
{
?>

<?php
    $_SESSION['miesiac'] = date('m');
    $_SESSION['rok'] = date('Y');

    if (isset($_POST['wyszukiwarka']) && $_POST['wyszukiwarka'] == '1')
    {
        $_SESSION['miesiac'] = $_POST['miesiac'];
        $_SESSION['rok'] = $_POST['rok'];
    }

    $miesiac = $_SESSION['miesiac'];
    $rok = $_SESSION['rok'];

    if (isset($_POST['editor']) && $_POST['editor'] == '1')
    {
        $id_sprawozdania = $_POST['id_sprawozdania'];
        $publikacje = $_POST['publikacje'];
        $filmy = $_POST['filmy'];
        $odwiedziny = $_POST['odwiedziny'];
        $studia = $_POST['studia'];
        $godziny = $_POST['godziny'];
    }

    require "ConnectToDB.php";
    $kwerenda_suma = "call SprawozdaniaSuma($id_uzytkownika, $miesiac, $rok)";
    $wynik_kwerenda_suma = mysqli_query($link, $kwerenda_suma);
    $komorka_kwerenda_suma = mysqli_fetch_array($wynik_kwerenda_suma); ?>


<!DOCTYPE html>
<html lang="pl">

<head>
  <title>Sprawozdania</title>
<?php require "czesci/head"; ?>
</head>
<?php require "czesci/navbar_glowny"; ?>
<?php require "czesci/navbar_sprawozdania"; ?>
<body>
  <div class="table-responsive">
      <table class="table table-dark text-center justify-content-center">
      <thead>
      <tr>  
      <form class="" action="Sprawozdania.php" method="post">
          <td colspan="11" class="font-weight-bold">Wyszukiwarka</td>
</tr> 
          <tr> 
          <td colspan="4">
            <select name='miesiac' id="miesiac">
              <?php
    for ($i = 1;$i < 13;$i++)
    {
        if ($i == $miesiac)
        {
            $t = "selected='selected'";
        }
        else
        {
            $t = '';
        }

        switch ($i)
        {
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
          <td colspan="3">
            <select name='rok' id="rok">

              <?php
    for ($i = 2019;$i < 2029;$i++)
    {
        if ($i == $rok)
        {
            echo "<option selected='selected' value='$i'>$i</option>";
        }
        else
        {
            echo "<option value='$i'>$i</option>";
        }
    } ?>
            </select>
          </td>

          <?php
    $m = date('m');
    $r = date('Y');

    if ($miesiac < 10)
    {
        $miesiac = '0' . $miesiac;
    }

    if ($miesiac != $m || $rok != $r)
    {
        echo '<input type="hidden" name="wyszukiwarka" value="0">';
    }
    else
    {
        echo '<input type="hidden" name="wyszukiwarka" value="1">';
    } ?>

          <td colspan="4"><input type="submit" value="Wyszukaj / Reset"></td>
        </form>
      </tr>

      <tr>
        <th scope="col">Lp.</th>
        <th scope="col">Kto</th>
        <th scope="col">Kiedy</th>
        <th scope="col">Typ</th>
        <th scope="col">Publikacje</th>
        <th scope="col">Filmy</th>
        <th scope="col">Odwiedziny</th>
        <th scope="col">Studia</th>
        <th scope="col">Godziny</th>
        <th scope="col">Edycja</th>
        <th scope="col">Bilans czasu</th>
      </tr>
      </thead>
<tbody>
      <?php
    $lp = 1;
    $ostatni_data = $rok . $miesiac . "01";
    $ostatni = date("t", strtotime($ostatni_data));

    for ($dzien = 1;$dzien <= $ostatni;$dzien++)
    {
        require "ConnectToDB.php";
        $kwerenda_osobyWSluzbie = "call osobyWSluzbie ($id_uzytkownika,$dzien, $miesiac, $rok);";
        $wynik_osobyWSluzbie = mysqli_query($link, $kwerenda_osobyWSluzbie);

        if (mysqli_num_rows($wynik_osobyWSluzbie) != 0)
        {

            while ($komorka_osobyWSluzbie = mysqli_fetch_array($wynik_osobyWSluzbie))
            {
                $osobyWSluzbie = $komorka_osobyWSluzbie['osobyWSluzbie'];
                $osobyWSluzbie = $osobyWSluzbie;

                $id_sluzby = $komorka_osobyWSluzbie['id_sluzby'];

                echo "<tr>";
                echo "<th scope='row' class='font-weight-bold vertical-center' rowspan=" . $osobyWSluzbie . ">" . $lp++ . "</th>";

                require "ConnectToDB.php";
                $kwerenda_SprawozdaniaLista = "call SprawozdaniaLista ($id_sluzby);";
                $wynik_SprawozdaniaLista = mysqli_query($link, $kwerenda_SprawozdaniaLista);

                while ($komorka_SprawozdaniaLista = mysqli_fetch_array($wynik_SprawozdaniaLista))
                {

                    echo "<td class='font-weight-bold'><a href='InfoOsoba.php?id_osoby=" . $komorka_SprawozdaniaLista['id_osoby'] . "'>" . $komorka_SprawozdaniaLista['kto'] . "</a></font></td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['kiedy_sluzba'] . "</td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['nazwa_typu'] . "</td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['publikacje'] . "</td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['filmy'] . "</td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['odwiedziny'] . "</td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['studia'] . "</td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['godziny'] . "</td>";
                    echo "<td class='vertical-center' rowspan=" . $osobyWSluzbie . "><a href='DodajSprawozdanie.php?id_sprawozdania=" . $komorka_SprawozdaniaLista['id_sprawozdania'] . "'>Edytuj</a></font></td>";

                    if ($komorka_SprawozdaniaLista['bilans_oczekiwania_rzeczywistosc'] >= '00:00')
                    {
                        echo "<td class='bg-success font-weight-bold vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['bilans_oczekiwania_rzeczywistosc'] . "</td>";
                    }
                    else
                    {
                        echo "<td class='bg-danger font-weight-bold vertical-center' rowspan=" . $osobyWSluzbie . ">" . $komorka_SprawozdaniaLista['bilans_oczekiwania_rzeczywistosc'] . "</td>";
                    }
                    echo "</tr>";
                }

                require "ConnectToDB.php";
                $kwerenda_SprawozdaniaOsoby = "call SprawozdaniaOsoby ($id_sluzby);";
                $wynik_SprawozdaniaOsoby = mysqli_query($link, $kwerenda_SprawozdaniaOsoby);
                while ($komorka_SprawozdaniaOsoby = mysqli_fetch_array($wynik_SprawozdaniaOsoby))
                {
                    echo "<tr>";
                    echo "<td class='font-weight-bold'><a href='InfoOsoba.php?id_osoby=" . $komorka_SprawozdaniaOsoby['id_osoby'] . "'>" . $komorka_SprawozdaniaOsoby['kto'] . "</a></font></td>";
                    echo "</tr>";
                }
            }
        }
    }
    echo "<tr class='font-weight-bold'>";
    echo "<td colspan='4'>SUMA</td>";
    echo "<td>" . $komorka_kwerenda_suma['s_publikacje'] . "</b</td>";
    echo "<td>" . $komorka_kwerenda_suma['s_filmy'] . "</b</td>";
    echo "<td>" . $komorka_kwerenda_suma['s_odwiedziny'] . "</b</td>";
    echo "<td>" . $komorka_kwerenda_suma['s_studia'] . "</b</td>";
    echo "<td colspan='2'>" . $komorka_kwerenda_suma['s_godziny'] . "</td>";

    require "ConnectToDB.php";
    $KwBilansPionieraSuma = "call BilansPionieraSuma ($id_uzytkownika, $miesiac, $rok);";
    $BilansPionieraSuma = mysqli_query($link, $KwBilansPionieraSuma);
    $bilans_typow = mysqli_fetch_array($BilansPionieraSuma);
    if ($bilans_typow['bilans_typow'] >= '00:00')
    {
        echo "<td colspan='1' class='bg-success'>" . $bilans_typow['bilans_typow'] . "</td>";
    }
    else
    {
        echo "<td colspan='1' class='bg-danger'>" . $bilans_typow['bilans_typow'] . "</td>";
    }
    echo "</tr>"; ?>
      <tr class='font-weight-bold'>
        <td colspan='8'>Miesięczny bilans godzin</td>
        <?php if ($komorka_kwerenda_suma['roznica_godzin'] >= 0)
    {
        echo "<td colspan='3' class='bg-success'>" . $komorka_kwerenda_suma['roznica_godzin'] . "</td>";
    }
    else
    {
        echo "<td colspan='3' class='bg-danger'>" . $komorka_kwerenda_suma['roznica_godzin'] . "</td>";
    } ?>
      </tr>

      <tr class='font-weight-bold'>
        <td colspan='8'>Suma służby z typów</td>
        <?php if ($komorka_kwerenda_suma['bilans_stypy_kwantum'] >= '00:00')
    {
        echo "<td colspan='3' class='bg-success'>" . $komorka_kwerenda_suma['s_typy'] . "</td>";
    }
    else
    {
        echo "<td colspan='3' class='bg-danger'>" . $komorka_kwerenda_suma['s_typy'] . "</td>";
    } ?>
      </tr>

      <tr class='font-weight-bold'>
        <td colspan='8'>Nadmiar / niedobór godzin na dzień <?php echo date("d-m-Y"); ?></td>
        <?php
    $bilans_rzeczywisty = $komorka_kwerenda_suma['bilans_rzeczywisty'];
    if ($bilans_rzeczywisty >= '00:00')
    {
        echo "<td colspan='3' class='bg-success'>" . $komorka_kwerenda_suma['bilans_rzeczywisty'] . "</td>";
    }
    else
    {
        echo "<td colspan='3' class='bg-danger'>" . $komorka_kwerenda_suma['bilans_rzeczywisty'] . "</td>";
    } ?>
      </tr>

      <tr class='font-weight-bold'>
        <td colspan='8'>Cel dzienny na dzień <?php echo date("d-m-Y"); ?></td>
        <?php
    $rzeczywisty_cel_dzienny = $komorka_kwerenda_suma['rzeczywisty_cel_dzienny'];
    if ($rzeczywisty_cel_dzienny >= '00:00')
    {
        echo "<td colspan='3' class='bg-danger'>" . $komorka_kwerenda_suma['rzeczywisty_cel_dzienny'] . "</td>";
    }
    else
    {
        echo "<td colspan='3' class='bg-success'>00:00</td>";
    } ?>

      </tr>

    </table>
  </div>
</body>

</html>

<?php
}
else
{
    header("refresh:0;url=Logowanie.php?skad=Sprawozdania.php");
}

?>
