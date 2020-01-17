<?php
if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

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
    $kwerenda_api = "call TelegramToken;";
    $wynik_api=mysqli_query($link, $kwerenda_api);
    $komorka_api = mysqli_fetch_array($wynik_api);
    $token = $komorka_api['token'];

    require "ConnectToDB.php";
    $kwerenda_mess = "call TelegramWysylkaWiadomosci;";
    $wynik_mess=mysqli_query($link, $kwerenda_mess);

$i=0;
    while ($komorka_mess = mysqli_fetch_array($wynik_mess)) {
        $messaggio = $komorka_mess['tresc'];
        $chatid = $komorka_mess['telegram_chat_id'];
        sendMessage($chatid, $messaggio, $token);
        $i++;
    }

require "ConnectToDB.php";
$kwerenda_kontrolna = "call TelegramKontrola";
$wynik_kontrola = mysqli_query($link, $kwerenda_kontrolna);

while ($komorka_kontrola = mysqli_fetch_array($wynik_kontrola)) {
    $chatid = $komorka_kontrola['telegram_chat_id'];
    $messaggio = $komorka_kontrola['tresc']. ', w ilości: '.$i;
    sendMessage($chatid, $messaggio, $token);
}
