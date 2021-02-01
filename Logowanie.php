<?php
if(isset($_GET["skad"]))
{
   $skad = $_GET["skad"];
}
else
{
   $skad = "index.php";
}



if (isset($_POST["logowanie"]) && $_POST["logowanie"]=="in")
{
   require __DIR__ . '/vendor/autoload.php';
   require_once ('ConnectToDB.php');
   require_once ('auth.php');
   
   $nazwa = mysqli_real_escape_string($link,$_POST["nazwa"]);
   $haslo = mysqli_real_escape_string($link,$_POST["haslo"]);
   $codigo_verificador = mysqli_real_escape_string($link,$_POST["codigo"]);

        $kwerenda_kod = "SELECT id_uzytkownika, GAuth, haslo FROM uzytkownicy where nazwa = '$nazwa'";
        $wynik_kod = mysqli_query($link, $kwerenda_kod);
        $tablica_kod = mysqli_fetch_array($wynik_kod);

        if (!empty($tablica_kod)) {
        $codigo_secreto = $tablica_kod['GAuth'];
        $haslo_hash = $tablica_kod['haslo'];
        $id_uzytkownika = $tablica_kod['id_uzytkownika'];

        $autenticador = new PHPGangsta_GoogleAuthenticator();
        $resultado = $autenticador->verifyCode($codigo_secreto, $codigo_verificador, 1);
        $haslo_test = password_verify($haslo, $haslo_hash);

        if ($resultado && $haslo_test)
        {
            $_SESSION['TOTP'] = 'JW';
            $_SESSION['id_uzytkownika'] = $id_uzytkownika;
            setcookie("SluzbyTOTP", "JW", time() + (86400 * 360));
            setcookie("SluzbyID", $id_uzytkownika, time() + (86400 * 360));
            header("refresh:0;url=$skad");
        }
        else
        {
            echo '<script language="javascript">';
            echo 'alert("Błąd logowania")';
            echo '</script>';
        }
        }
        else{
           echo '<script language="javascript">';
            echo 'alert("Błąd logowania")';
            echo '</script>';
        }
   }

?>
<!DOCTYPE html>
<html lang="pl">

<head>
   <title>Logowanie</title>
   <?php require "czesci/head";?>
   <meta name="google-signin-scope" content="profile email">
   <meta name="google-signin-client_id" content="752103993244-mirv7g94es3uc998428cl4cmbgmi8rsm.apps.googleusercontent.com">
   <script src="https://apis.google.com/js/platform.js" async defer>
      {
         lang: 'pl'
      }
   </script>
</head>

<body>
   <div class="table-responsive">
      <table class="table table-dark text-center">
         <form action="Logowanie.php?skad=<?php echo $skad; ?>" method="post" name='formularz_logowanie'
            onsubmit="return sprawdzenieFormularzaLogowania()">
            <tr>
               <td>
                  <input type="text" name="nazwa" placeholder="Nazwa użytkownika" required>
               </td>
            </tr>
            <tr>
               <td>
                  <input type="password" name="haslo" placeholder="Hasło" required>
               </td>
            </tr>
            <tr>
               <td>
                  <input type="number" name="codigo" placeholder="Podaj kod z aplikacji" autocomplete="off" min="1"
                     max="999999" minlength="6" maxlength="6" required>
                  <input type="hidden" name="logowanie" value="in">
               </td>
            </tr>
            <tr>
               <td>
                  <div class="g-signin2 d-flex justify-content-center" data-onsuccess="onSignIn" data-theme="dark"></div>
               </td>
            </tr>
            <tr>
               <td>
                  <button id='btn_formularz_logowanie' class='font-weight-bold'>Wchodzę!</button>
               </td>
            </tr>
         </form>
      </table>
   </div>
</body>

</html>