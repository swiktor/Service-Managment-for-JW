<?php
require 'auth.php';

if (isset($_SESSION['TOTP']) && $_SESSION['TOTP']=='JW') {
    ?>

<?php

require 'kalendarzsync.php';
    $optParams = array(
  'maxResults' => 15,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);
    $results = $service->events->listEvents($calendarId, $optParams);
    $events = $results->getItems();

    $i =1; ?>
 <!DOCTYPE html>
 <html lang="pl" dir="ltr">
   <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta charset="utf-8">
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>Lista służb z kalendarza</title>
   </head>
   <body>
 <div id='tabelka_show' name='tabelka_show'>
 <table border=1>
   <tr>
     <td colspan="4"><font color='black' style="font-weight:bold"><a color='black' href='index.php'>Strona główna</a></font></td>
   </tr>
 <tr>
 <!-- <th>Lp.</th> -->
 <th>Kto</th>
 <th>Typ</th>
 <th>Od</th>
 <th>Do</th>
 </tr>

 <?php
 if (empty($events)) {
     print "No upcoming events found.\n";
 } else {
     foreach ($events as $event) {
         // $start = $event->start->dateTime ." - ". $event->end->dateTime;
         echo "<tr>";
         // echo "<td>".$i++."</td>";
         echo "<td>".$event->summary."</td>";
         echo "<td>".$event->description."</td>";
         echo "<td>".$event->start->dateTime."</td>";
         echo "<td>".$event->end->dateTime."</td>";
         // echo "<td>".$event->id."</td>";
         echo "</tr>";

         if (empty($start)) {
             $start = $event->start->date."<br>";
         }
         // printf("%s (%s)\r\n", "<br>".$event->getSummary(), $start);
     }
 } ?>
</table>
</div>
</html>

<?php
} else {
     header("refresh:0;url=GAuth/Logowanie.php?skad=ListaKalendarz.php");
 }

 ?>
