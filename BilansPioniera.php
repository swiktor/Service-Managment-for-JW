<?php
   require 'auth.php';
   
   if (isset($_SESSION['TOTP']) && $_SESSION['TOTP'] = 'JW')
   {?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <title>Bilans pioniera</title>
    <?php require "czesci/head";?>
</head>

<body>
    <?php require "czesci/navbar_glowny";?>

    <div class="table-responsive">
        <table class="table table-dark text-center">
            <thead>
                <tr>
                    <th scope="col">Dzień</th>
                    <th scope="col">Godziny</th>
                    <th scope="col">Cel dzienny</th>
                    <th scope="col">Bilans</th>
                </tr>
            </thead>
            <tbody>
                <?php
               $miesiac = $_SESSION['miesiac'];
               $rok = $_SESSION['rok'];
               
               require "ConnectToDB.php";
               $KwBilansPioniera = "call BilansPioniera ($id_uzytkownika, 0,$miesiac,$rok)";
               $BilansPioniera = mysqli_query($link, $KwBilansPioniera);
               $ostatni = mysqli_fetch_array($BilansPioniera);
               $ostatni = $ostatni['ostatni'];
               
               for ($dzien = 1;$dzien <= $ostatni;$dzien++)
               {
                   echo "<tr>";
                   require "ConnectToDB.php";
                   $KwBilansPioniera = "call BilansPioniera ($id_uzytkownika,$dzien,$miesiac,$rok)";
                   $BilansPioniera = mysqli_query($link, $KwBilansPioniera);
               
                   $Pionier = mysqli_fetch_array($BilansPioniera);
                   $godziny = $Pionier['godziny'];
                   $cel_dzienny = $Pionier['cel_dzienny'];
                   $bilans = $Pionier['bilans'];
                   $nazwa_dnia = $Pionier['nazwa_dnia'];
               
                   switch ($nazwa_dnia)
                   {
                       case 'Monday':
                           $nazwa_dnia = 'Poniedziałek';
                       break;
               
                       case 'Tuesday':
                           $nazwa_dnia = 'Wtorek';
                       break;
               
                       case 'Wednesday':
                           $nazwa_dnia = 'Środa';
                       break;
               
                       case 'Thursday':
                           $nazwa_dnia = 'Czwartek';
                       break;
               
                       case 'Friday':
                           $nazwa_dnia = 'Piątek';
                       break;
               
                       case 'Saturday':
                           $nazwa_dnia = 'Sobota';
                       break;
               
                       case 'Sunday':
                           $nazwa_dnia = 'Niedziela';
                       break;
               
                       default:
                           $nazwa_dnia = '';
                       break;
               
                   }
               
                   if ($bilans >= '00:00')
                   {
                       echo "<th scope='row' class='bg-success'>" . $dzien . ' (' . $nazwa_dnia . ')' . "</th>";
                       echo "<td class='bg-success'>" . $godziny . "</td>";
                       echo "<td class='bg-success'>" . $cel_dzienny . "</td>";
                       echo "<td class='bg-success'>" . $bilans . "</td>";
                   }
               
                   if ($bilans <= '00:00' && $bilans != '-01:43' && !is_null($godziny))
                   {
                       echo "<th scope='row' class='bg-danger'>" . $dzien . ' (' . $nazwa_dnia . ')' . "</th>";
                       echo "<td class='bg-danger'>" . $godziny . "</td>";
                       echo "<td class='bg-danger'>" . $cel_dzienny . "</td>";
                       echo "<td class='bg-danger'>" . $bilans . "</td>";
                   }
               
                   if ($bilans == '-01:43')
                   {
                       echo "<th scope='row' class='bg-warning'>" . $dzien . ' (' . $nazwa_dnia . ')' . "</th>";
                       echo "<td class='bg-warning'>" . $godziny . "</td>";
                       echo "<td class='bg-warning'>" . $cel_dzienny . "</td>";
                       echo "<td class='bg-warning'>" . $bilans . "</td>";
                   }
               
                   if (is_null($godziny))
                   {
                       $godziny = '00:00';
                       $bilans = '00:00';
                       echo "<th scope='row'>" . $dzien . ' (' . $nazwa_dnia . ')' . "</th>";
                       echo "<td>" . $godziny . "</td>";
                       echo "<td>" . $cel_dzienny . "</td>";
                       echo "<td>" . $bilans . "</td>";
                   }
               }
               echo "</tr>"; ?>
                <tr>
                    <td class='font-weight-bold' colspan="3">Suma:</td>
                    <?php
                  require "ConnectToDB.php";
                  $KwBilansPionieraSuma = "call BilansPionieraSuma ($id_uzytkownika,$miesiac,$rok);";
                  $BilansPionieraSuma = mysqli_query($link, $KwBilansPionieraSuma);
                  $suma = mysqli_fetch_array($BilansPionieraSuma);
                  $suma = $suma['suma_celu'];
                  
                  if ($suma >= '00:00')
                  {
                      echo "<td colspan='2' class='font-weight-bold bg-success'>" . $suma . "</td>";
                  }
                  else
                  {
                      echo "<td colspan='2' class='font-weight-bold bg-warning'>" . $suma . "</td>";
                  } ?>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
   }
   else
   {
       header("refresh:0;url=Logowanie.php?skad=BilansPioniera.php");
   }?>