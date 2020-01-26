<?php
$skad = $_GET["skad"];

if (isset($_POST["logowanie"])) {
    $nazwa = $_POST["nazwa"];
    $haslo = $_POST["haslo"];
    $codigo_verificador = $_POST["codigo"];

    require_once('vendor/autoload.php');
    require_once('vendor/PHPGangsta/GoogleAuthenticator.php');

    require "../ConnectToDB.php";
    $kwerenda_kod = "SELECT id_uzytkownika, GAuth, haslo FROM jw.uzytkownicy where nazwa = '$nazwa'";
    $wynik_kod=mysqli_query($link, $kwerenda_kod);
    $tablica_kod = mysqli_fetch_array($wynik_kod);
    $codigo_secreto = $tablica_kod['GAuth'];
    $haslo_hash = $tablica_kod['haslo'];
    $id_uzytkownika=$tablica_kod['id_uzytkownika'];
    $autenticador = new PHPGangsta_GoogleAuthenticator();
    $resultado = $autenticador->verifyCode($codigo_secreto, $codigo_verificador, 1);
    $haslo_test = password_verify($haslo, $haslo_hash);

    if ($resultado && $haslo_test) {
        session_start();
        $_SESSION['TOTP']='JW';
        $_SESSION['id_uzytkownika']=$id_uzytkownika;
        setcookie("SluzbyTOTP", "JW", time() + (86400 * 7), "/");
        setcookie("SluzbyID", $id_uzytkownika, time() + (86400 * 7), "/");
        $link = "../".$skad;
        header("refresh:0;url=$link");

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

require "../ConnectToDB.php";
$QueryAddLog="call LogAdd($id_uzytkownika,'User login','$ip');";
mysqli_query($link, $QueryAddLog);
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
<form action="Logowanie.php?skad=<?php echo $skad; ?>" method="post" autocomplete="off">
<input type="text" name="nazwa" placeholder="Nazwa użytkownika">
<br>
<input type="password" name="haslo" placeholder="Hasło">
<br>
	<input type="text" name="codigo" placeholder="Podaj kod z aplikacji" autocomplete="off">
  <input type="hidden" name="logowanie" value="in">
<br>
	<button>Wchodzę!</button>
</form>
</div>
</body>
</html>
