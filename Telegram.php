<?php
if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

require "ConnectToDB.php";
$kwerenda_api = "call TelegramToken;";
$wynik_api=mysqli_query($link, $kwerenda_api);
$komorka_api = mysqli_fetch_array($wynik_api);
$token = $komorka_api['token'];

function sendMessage($chatID, $messaggio, $token)
{
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
    $url = $url . "&text=" . urlencode($messaggio);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

    require "ConnectToDB.php";
    $kwerenda_mess = "call TelegramSluzby;";
    $wynik_mess=mysqli_query($link, $kwerenda_mess);

$i=0;
    while ($komorka_mess = mysqli_fetch_array($wynik_mess)) {
        $messaggio = $komorka_mess['tresc'];
        $chatid = $komorka_mess['telegram_chat_id'];
        sendMessage($chatid, $messaggio, $token);
        $i++;
    }

require "ConnectToDB.php";
$kwerenda_sprawozdanie = "call TelegramSprawozdania;";
$wynik_sprawozdanie=mysqli_query($link, $kwerenda_sprawozdanie);

while ($komorka_sprawozdanie = mysqli_fetch_array($wynik_sprawozdanie)) {
    $ostatni = $komorka_sprawozdanie['ostatni'];
    $dzis = $komorka_sprawozdanie['dzis'];
    if ($ostatni == $dzis) {
        $chatid = $komorka_sprawozdanie['telegram_chat_id'];
        $messaggio =  $komorka_sprawozdanie['tresc_sprawozdania'];
        sendMessage($chatid, $messaggio, $token);
        $i++;

        $messaggio = $komorka_sprawozdanie['minuty'];
        sendMessage($chatid, $messaggio, $token);
        $i++;

        $kto = $komorka_sprawozdanie['id_osoby'];
        $typ = '1';
        $kiedy = $komorka_sprawozdanie['jutro'];
        $uzytkownik = $komorka_sprawozdanie['id_uzytkownika'];
        $minuty = $komorka_sprawozdanie['minuty_do_przeniesienia'];

        require "ConnectToDB.php";
        $KwDodajSluzbeMinuty = "INSERT INTO sluzby VALUES (NULL, $kto, $typ, '$kiedy', CURRENT_TIMESTAMP(), $uzytkownik, 'id_gcal');";
        $DodajSluzbeMinuty = mysqli_query($link, $KwDodajSluzbeMinuty);

        if ($DodajSluzbeMinuty) {
            require "ConnectToDB.php";
            $kwerenda_kalendarz = "CALL DaneDoKalendarza($kto, $typ,'$kiedy',$uzytkownik)";
            $wynik_kalendarz = mysqli_query($link, $kwerenda_kalendarz);
            $komorka_kalendarz = mysqli_fetch_array($wynik_kalendarz);
            $id_sprawozdania = $komorka_kalendarz['id_sprawozdania'];

            require "ConnectToDB.php";
            $kwerenda_spr_add = "UPDATE sprawozdania SET publikacje='0',filmy='0',odwiedziny='0',studia='0',godziny='$minuty' WHERE id_sprawozdania='$id_sprawozdania';";
            $wynik_spr_add = mysqli_query($link, $kwerenda_spr_add);
        }
    }
}

    require "ConnectToDB.php";
    $kwerenda_kontrolna = "call TelegramKontrola";
    $wynik_kontrola = mysqli_query($link, $kwerenda_kontrolna);

    $komorka_kontrola = mysqli_fetch_array($wynik_kontrola);
    $chatid = $komorka_kontrola['telegram_chat_id'];
    $messaggio = $komorka_kontrola['tresc']. ', w ilości: '.$i;
    sendMessage($chatid, $messaggio, $token);
