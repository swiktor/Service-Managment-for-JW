<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>

<?php
require "ConnectToDB.php";
    $kwerenda_show_sprawozdania = "call ListaSprawozdania ($id_uzytkownika);";
    $wynik_show_sprawozdania=mysqli_query($link, $kwerenda_show_sprawozdania);

    require "ConnectToDB.php";
    $kwerenda_suma = "call SprawozdanieSuma($id_uzytkownika)";
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
     <td colspan="9"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td>
 </tr>
 <tr>
   <td colspan='9'><b>Profil: <?php echo $komorka_kwerenda_suma['nazwa_celu']; ?></b></td>
 </tr>
   <tr>
     <th>Lp.</th>
   <th>Kto</th>
<th>Kiedy</th>
 <th>Publikacje</th>
 <th>Filmy</th>
 <th>Odwiedziny</th>
 <th>Studia</th>
  <th>Godziny</th>
  <th>Edycja</th>
 </tr>

 <?php
 $i =1;
    while ($komorka_show_sprawozdania = mysqli_fetch_array($wynik_show_sprawozdania)) {
        echo "<tr>";
        echo "<td>".$i++."</td>";
        echo "<td>".$komorka_show_sprawozdania['kto']."</td>";
        echo "<td>".$komorka_show_sprawozdania['kiedy_sluzba']."</td>";
        echo "<td>".$komorka_show_sprawozdania['publikacje']."</td>";
        echo "<td>".$komorka_show_sprawozdania['filmy']."</td>";
        echo "<td>".$komorka_show_sprawozdania['odwiedziny']."</td>";
        echo "<td>".$komorka_show_sprawozdania['studia']."</td>";
        echo "<td>".$komorka_show_sprawozdania['godziny']."</td>";
        echo "<td><a color='black' href='DodajSprawozdanie.php?id_sprawozdania=".$komorka_show_sprawozdania['id_sprawozdania']."'>Edytuj</a></font></td>";
        echo "</tr>";
    }


    echo "<tr>";
    echo "<td colspan='3'><b>SUMA</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_publikacje']."</b</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_filmy']."</b</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_odwiedziny']."</b</td>";
    echo "<td><b>".$komorka_kwerenda_suma['s_studia']."</b</td>";
    echo "<td colspan='2'><b>".$komorka_kwerenda_suma['s_godziny']."</b></td>";
    echo "</tr>"; ?>
    <tr>
      <td colspan='7'><b>Miesięczny bilans godzin</b></td>
        <?php if ($komorka_kwerenda_suma['roznica_godzin']>=0)
      {
        echo "<td colspan='2' bgcolor='#90EE90'><b>".$komorka_kwerenda_suma['roznica_godzin']."</b></td>";
      }
      else {
        echo "<td colspan='2' bgcolor='#ffcccb'><b>".$komorka_kwerenda_suma['roznica_godzin']."</b></td>";
      }
      ?>
    </tr>

    <tr>
      <td colspan='7'><b>Nadmiar / niedobór godzin na dzień <?php echo date("d-m-Y"); ?></b></td>
<?php if ($komorka_kwerenda_suma['bilans_rzeczywisty']>=0)
{
  echo "<td colspan='2' bgcolor='#90EE90'><b>".$komorka_kwerenda_suma['bilans_rzeczywisty']."</b></td>";
}
else {
  echo "<td colspan='2' bgcolor='#ffcccb'><b>".$komorka_kwerenda_suma['bilans_rzeczywisty']."</b></td>";
}
?>
    </tr>

    <tr>
      <td colspan='7'><b>Cel dzienny na dzień <?php echo date("d-m-Y"); ?></b></td>
      <td colspan='2'><b><?php echo $komorka_kwerenda_suma['rzeczywisty_cel_dzienny']; ?></b></td>
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
