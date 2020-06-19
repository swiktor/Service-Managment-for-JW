<?php
   $skad = $_GET["skad"];
   
   if (isset($_POST["logowanie"])) {
       $nazwa = $_POST["nazwa"];
       $haslo = $_POST["haslo"];
       $codigo_verificador = $_POST["codigo"];
   
       require_once('vendor/autoload.php');
       require_once('vendor/PHPGangsta/GoogleAuthenticator.php');
   
       require_once('../ConnectToDB.php');
       require_once('../auth.php');
       
       $kwerenda_kod = "SELECT id_uzytkownika, GAuth, haslo FROM uzytkownicy where nazwa = '$nazwa'";      
       $wynik_kod=mysqli_query($link, $kwerenda_kod);
       $tablica_kod = mysqli_fetch_array($wynik_kod);
       $codigo_secreto = $tablica_kod['GAuth'];
       $haslo_hash = $tablica_kod['haslo'];
       $id_uzytkownika=$tablica_kod['id_uzytkownika'];
   
       $autenticador = new PHPGangsta_GoogleAuthenticator();
       $resultado = $autenticador->verifyCode($codigo_secreto, $codigo_verificador, 1);
       $haslo_test = password_verify($haslo, $haslo_hash);
   
       if ($resultado && $haslo_test) {
           $_SESSION['TOTP']='JW';
           $_SESSION['id_uzytkownika']=$id_uzytkownika;
           setcookie("SluzbyTOTP", "JW", time() + (86400 * 7), "/");
           setcookie("SluzbyID", $id_uzytkownika, time() + (86400 * 7), "/");
           $link = "../".$skad;
           header("refresh:0;url=$link");
       } else {
           echo '<script language="javascript">';
           echo 'alert("Błędny kod")';
           echo '</script>';
       }
   }
?>

<!DOCTYPE html>
<html lang="pl">
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8">
      <title>Logowanie</title>
      <link rel="stylesheet" type="text/css" href="../style.css">
   </head>
   <body>
      <div id='tabelka_show' name='tabelka_show'>
         <table border =1>
            <form action="Logowanie.php?skad=<?php echo $skad; ?>" method="post" autocomplete="off">
            <tr>
               <td>
                  <input type="text" name="nazwa" placeholder="Nazwa użytkownika">
               </td>
            </tr>
            <tr>
               <td>
                  <input type="password" name="haslo" placeholder="Hasło">
               </td>
            </tr>
            <tr>
               <td>
                  <input type="text" name="codigo" placeholder="Podaj kod z aplikacji" autocomplete="off">
                  <input type="hidden" name="logowanie" value="in">
               </td>
            </tr>
            <tr>
               <td>
                  <button><b>Wchodzę!<b></button>
               </td>
            </tr>
         </table>
      </div>
   </body>
</html>