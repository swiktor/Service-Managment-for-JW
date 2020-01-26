<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>

<?php
    require "ConnectToDB.php";
    $kwerenda_show_sprawozdania = "call SprawozdaniaLista ($id_uzytkownika);";
    $wynik_show_sprawozdania=mysqli_query($link, $kwerenda_show_sprawozdania);

    require "ConnectToDB.php";
    $kwerenda_suma = "call SprawozdaniaSuma($id_uzytkownika)";
    $wynik_kwerenda_suma = mysqli_query($link, $kwerenda_suma);
    $komorka_kwerenda_suma = mysqli_fetch_array($wynik_kwerenda_suma); ?>

 <!DOCTYPE html>
 <html lang="pl">
   <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta charset="utf-8">
     <title>Sprawozdania</title>
 <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   <body>
 <div id='tabelka_show' name='tabelka_show'>
 <table border=1>

   <tr>
     <!-- <td colspan="10"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td> -->
     <td colspan="11"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td>
 </tr>
 <tr>
   <!-- <td colspan='10'><b>Profil: <?php echo $komorka_kwerenda_suma['nazwa_celu']; ?></b></td> -->
   <td colspan='11'><b>Profil: <a color='black' href='ZmienCel.php?id_uzytkownika=<?php echo $komorka_kwerenda_suma['id_uzytkownika']; ?>&id_celu=<?php echo $komorka_kwerenda_suma['id_celu']; ?>'><?php echo $komorka_kwerenda_suma['pelna_nazwa_celu']; ?></a></b></td>

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
  <!-- <th>Planowy czas</th> -->
  <th>Bilans czasu</th>
 </tr>

 <?php
 $i =1;
    while ($komorka_show_sprawozdania = mysqli_fetch_array($wynik_show_sprawozdania)) {
        echo "<tr>";
        echo "<td>".$i++."</td>";
        echo "<td><a color='black' href='InfoOsoba.php?id_osoby=".$komorka_show_sprawozdania['id_osoby']."'>".$komorka_show_sprawozdania['kto']."</a></font></td>";
        echo "<td>".$komorka_show_sprawozdania['kiedy_sluzba']."</td>";
        echo "<td>".$komorka_show_sprawozdania['nazwa_typu']."</td>";
        echo "<td>".$komorka_show_sprawozdania['publikacje']."</td>";
        echo "<td>".$komorka_show_sprawozdania['filmy']."</td>";
        echo "<td>".$komorka_show_sprawozdania['odwiedziny']."</td>";
        echo "<td>".$komorka_show_sprawozdania['studia']."</td>";
        echo "<td>".$komorka_show_sprawozdania['godziny']."</td>";
        echo "<td><a color='black' href='DodajSprawozdanie.php?id_sprawozdania=".$komorka_show_sprawozdania['id_sprawozdania']."'>Edytuj</a></font></td>";
        // echo "<td>".$komorka_show_sprawozdania['czas_trwania']."</td>";
        if ($komorka_show_sprawozdania['bilans_oczekiwania_rzeczywistosc']>='00:00') {
            echo "<td bgcolor='#90EE90'><b>".$komorka_show_sprawozdania['bilans_oczekiwania_rzeczywistosc']."</b></td>";
        } else {
            echo "<td bgcolor='#ffcccb'><b>".$komorka_show_sprawozdania['bilans_oczekiwania_rzeczywistosc']."</b></td>";
        }
        echo "</tr>";
    }

    echo "<tr>";
    echo "<td colspan='4'><b>SUMA</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_publikacje']."</b</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_filmy']."</b</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_odwiedziny']."</b</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_studia']."</b</td>";
    echo "<td colspan='2'><b>".$komorka_kwerenda_suma['s_godziny']."</b></td>";
    echo "</tr>"; ?>
    <tr>
      <td colspan='8'><b>Miesięczny bilans godzin</b></td>
        <?php if ($komorka_kwerenda_suma['roznica_godzin']>=0) {
        echo "<td colspan='2' bgcolor='#90EE90'><b>".$komorka_kwerenda_suma['roznica_godzin']."</b></td>";
    } else {
        echo "<td colspan='2' bgcolor='#ffcccb'><b>".$komorka_kwerenda_suma['roznica_godzin']."</b></td>";
    } ?>
    </tr>

    <tr>
      <td colspan='8'><b>Nadmiar / niedobór godzin na dzień <?php echo date("d-m-Y"); ?></b></td>
<?php
$bilans_rzeczywisty = $komorka_kwerenda_suma['bilans_rzeczywisty'];
    if ($bilans_rzeczywisty>='00:00:00') {
        echo "<td colspan='2' bgcolor='#90EE90'><b>".$komorka_kwerenda_suma['bilans_rzeczywisty']."</b></td>";
    } else {
        echo "<td colspan='2' bgcolor='#ffcccb'><b>".$komorka_kwerenda_suma['bilans_rzeczywisty']."</b></td>";
    } ?>
    </tr>

    <tr>
      <td colspan='8'><b>Cel dzienny na dzień <?php echo date("d-m-Y"); ?></b></td>
            <?php
      $rzeczywisty_cel_dzienny = $komorka_kwerenda_suma['rzeczywisty_cel_dzienny'];
    if ($rzeczywisty_cel_dzienny>='00:00:00') {
        echo "<td colspan='2' bgcolor='#ffcccb'><b>".$komorka_kwerenda_suma['rzeczywisty_cel_dzienny']."</b></td>";
    } else {
        // echo "<td colspan='2' bgcolor='#90EE90'><b>".$komorka_kwerenda_suma['rzeczywisty_cel_dzienny']."</b></td>";
        echo "<td colspan='2' bgcolor='#90EE90'><b>00:00:00</b></td>";
    } ?>


    </tr>

    <!-- <tr>
      <td colspan='9'><b>Suma liczona z typów służb</b></td>
      <td colspan='10'><b>Suma liczona z typów służb</b></td>
      <td colspan='1'><b><?php echo $komorka_kwerenda_suma['s_typy']; ?></b></td>
    </tr> -->

    <tr>
      <!-- <td colspan='9'><b>Planowany a rzeczywisty czas w służbie</b></td> -->
      <td colspan='10'><b>Bilans - oczekiwania a rzeczywistość czasu</b></td>
            <?php
$bilans_oczekiwania_rzeczywistosc= $komorka_kwerenda_suma['bilans_oczekiwania_rzeczywistosc'];
    if ($bilans_oczekiwania_rzeczywistosc>='00:00') {
        echo "<td colspan='1' bgcolor='#90EE90'><b>".$komorka_kwerenda_suma['bilans_oczekiwania_rzeczywistosc']."</b></td>";
    } else {
        echo "<td colspan='1' bgcolor='#ffcccb'><b>".$komorka_kwerenda_suma['bilans_oczekiwania_rzeczywistosc']."</b></td>";
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
