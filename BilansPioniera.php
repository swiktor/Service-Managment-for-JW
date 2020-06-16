<?php
   require 'auth.php';
   
   if (isset($_SESSION['TOTP']) && $_SESSION['TOTP'] = 'JW')
   {?>

<!DOCTYPE html>
<html lang="pl">
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <title>Bilans pioniera</title>
      <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   <body>
      <div id='tabelka_show' name='tabelka_show'>
         <table border=1>
            <tr>
               <td colspan="2"><a color='black' href='index.php'>Strona główna</a></td>
               <td colspan="2"><a color='black' href='Sprawozdania.php'>Sprawozdania</a></td>
            </tr>
            <tr>
               <th>Dzień</th>
               <th>Godziny</th>
               <th>Cel dzienny</th>
               <th>Bilans</th>
            </tr>
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
                       echo "<td bgcolor='#90EE90'><b>" . $dzien . ' </b>(' . $nazwa_dnia . ')' . "</td>";
                       echo "<td bgcolor='#90EE90'>" . $godziny . "</td>";
                       echo "<td bgcolor='#90EE90'>" . $cel_dzienny . "</td>";
                       echo "<td bgcolor='#90EE90'>" . $bilans . "</td>";
                   }
               
                   if ($bilans <= '00:00' && $bilans != '-01:43' && !is_null($godziny))
                   {
                       echo "<td bgcolor='#ffcccb'><b>" . $dzien . ' </b>(' . $nazwa_dnia . ')' . "</b></td>";
                       echo "<td bgcolor='#ffcccb'>" . $godziny . "</td>";
                       echo "<td bgcolor='#ffcccb'>" . $cel_dzienny . "</td>";
                       echo "<td bgcolor='#ffcccb'>" . $bilans . "</td>";
                   }
               
                   if ($bilans == '-01:43')
                   {
                       echo "<td bgcolor='#FFFFE0'><b>" . $dzien . ' </b>(' . $nazwa_dnia . ')' . "</b></td>";
                       echo "<td bgcolor='#FFFFE0'>" . $godziny . "</td>";
                       echo "<td bgcolor='#FFFFE0'>" . $cel_dzienny . "</td>";
                       echo "<td bgcolor='#FFFFE0'>" . $bilans . "</td>";
                   }
               
                   if (is_null($godziny))
                   {
                       $godziny = '00:00';
                       $bilans = '00:00';
                       echo "<td bgcolor='#d3d3d3'><b>" . $dzien . ' </b>(' . $nazwa_dnia . ')' . "</b></td>";
                       echo "<td bgcolor='#d3d3d3'>" . $godziny . "</td>";
                       echo "<td bgcolor='#d3d3d3'>" . $cel_dzienny . "</td>";
                       echo "<td bgcolor='#d3d3d3'>" . $bilans . "</td>";
                   }
               }
               echo "</tr>"; ?>
            <tr>
               <td colspan="3"><font color='black' style="font-weight:bold">Suma:</font></td>
               <?php
                  require "ConnectToDB.php";
                  $KwBilansPionieraSuma = "call BilansPionieraSuma ($id_uzytkownika,$miesiac,$rok);";
                  $BilansPionieraSuma = mysqli_query($link, $KwBilansPionieraSuma);
                  $suma = mysqli_fetch_array($BilansPionieraSuma);
                  $suma = $suma['suma_celu'];
                  
                  if ($suma >= '00:00')
                  {
                      echo "<td colspan='2' bgcolor='#90EE90'><b>" . $suma . "</b></td>";
                  }
                  else
                  {
                      echo "<td colspan='2' bgcolor='#ffcccb'><b>" . $suma . "</b></td>";
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
       header("refresh:0;url=GAuth/Logowanie.php?skad=BilansPioniera.php");
   }?>
