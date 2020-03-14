<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']='JW') {
    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <title>Moje tereny</title>
      <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
    <div id='tabelka_show' name='tabelka_show'>
    <table border=1>
      <tr>
        <td colspan="7"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td>
      </tr>
      <tr>
        <th>Numer terenu</th>
        <th>Nazwa</th>
        <th>Mapa</th>
        <th>Data wypożyczenia</th>
        <th>2 miesięce</th>
        <th>Deadline</th>
        <th>Data oddania</th>
      </tr>
<?php
require "ConnectToDB.php";
    $KwTerenyOsobowe = "call TerenyOsobowe ($id_uzytkownika) ";
    $TerenyOsobowe = mysqli_query($link, $KwTerenyOsobowe);

    while ($KomTerenyOsobowe = mysqli_fetch_array($TerenyOsobowe)) {
        echo "<tr>";
        echo "<td>".$KomTerenyOsobowe['nr_terenu']."</td>";
        echo "<td>".$KomTerenyOsobowe['nazwa']."</td>";
        echo "<td><a href=".$KomTerenyOsobowe['mapa'].">Link</a></td>";
        echo "<td>".$KomTerenyOsobowe['kiedy_pobrano']."</td>";

        if ($KomTerenyOsobowe['bilans_dwa_msc']>=0) {
            echo "<td bgcolor='#90EE90'>".$KomTerenyOsobowe['dwa_msc']."</td>";
        } else {
            echo "<td bgcolor='#ffcccb'>".$KomTerenyOsobowe['dwa_msc']."</td>";
        }

        if ($KomTerenyOsobowe['bilans_cztery_msc']>=0) {
            echo "<td bgcolor='#90EE90'>".$KomTerenyOsobowe['cztery_msc']."</td>";
        } else {
            echo "<td bgcolor='#ffcccb'>".$KomTerenyOsobowe['cztery_msc']."</td>";
        }
        echo "<td>".$KomTerenyOsobowe['kiedy_oddano']."</td>";
        echo "</tr>";
    } ?>


    </table>
    </div>
    </body>
    </html>








    <?php
} else {
        header("refresh:0;url=GAuth/Logowanie.php?skad=BilansPioniera.php");
    }
       ?>
