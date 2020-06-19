SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `JI` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `JI`;

DELIMITER $$
DROP PROCEDURE IF EXISTS `BilansPioniera`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `BilansPioniera` (`uzytkownik` INT, `dzien` INT, `miesiac` INT, `rok` INT)  BEGIN
select
TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny))), "%H:%i") as godziny,
TIME_FORMAT(sec_to_time(ceil(time_to_sec(kwantum) / (DAYOFMONTH(LAST_DAY(concat(rok,'-',miesiac,'-01')))))), "%H:%i") as cel_dzienny,
TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny)) - (ceil(time_to_sec(kwantum) / (DAYOFMONTH(LAST_DAY(concat(rok,'-',miesiac,'-01'))))))), "%H:%i") as bilans,
DAYOFMONTH(LAST_DAY(concat(rok,'-',miesiac,'-01'))) as ostatni,
DAYNAME(concat(rok,'-',miesiac,'-',dzien)) as nazwa_dnia
from sprawozdania
inner join sluzby on sprawozdania.id_sluzby=sluzby.id_sluzby
inner join uzytkownicy on uzytkownicy.id_uzytkownika = sluzby.id_uzytkownika
inner join cele on uzytkownicy.id_celu = cele.id_celu
where
DAY(kiedy_sluzba) =dzien
AND
MONTH(kiedy_sluzba) = miesiac
AND
YEAR(kiedy_sluzba) = rok
and
sluzby.id_uzytkownika = uzytkownik;
END$$

DROP PROCEDURE IF EXISTS `BilansPionieraSuma`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `BilansPionieraSuma` (`uzytkownik` INT, `miesiac` INT, `rok` INT)  BEGIN
select TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny)) - (ceil(time_to_sec(kwantum)/DAYOFMONTH(LAST_DAY(concat(rok,'-',miesiac,'-01'))))) *day(concat(rok,'-',miesiac,'-01'))), "%H:%i") as suma_celu,
TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny))-sum(time_to_sec(czas_trwania))), "%H:%i") as bilans_typow
from sprawozdania
inner join sluzby on sprawozdania.id_sluzby=sluzby.id_sluzby 
inner join uzytkownicy on uzytkownicy.id_uzytkownika = sluzby.id_uzytkownika
inner join cele on uzytkownicy.id_celu = cele.id_celu
inner join typy on sluzby.id_typu = typy.id_typu
where day(kiedy_sluzba) between '01' and day(CURRENT_TIMESTAMP())
AND
MONTH(kiedy_sluzba) = miesiac
AND 
YEAR(kiedy_sluzba) = rok
and 
sluzby.id_uzytkownika = uzytkownik;
END$$

DROP PROCEDURE IF EXISTS `DaneDoKalendarza`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `DaneDoKalendarza` (IN `osoba` INT, `typ` INT, `kiedy` DATETIME, `uzytkownik` INT)  BEGIN
SELECT sluzby.id_sluzby, concat(osoby.imie, ' ', osoby.nazwisko) as kto, nazwa_typu, kiedy_sluzba as kiedy_sluzba_od, ADDTIME(kiedy_sluzba, czas_trwania) as kiedy_sluzba_do FROM sluzby
inner join osoby on osoby.id_osoby=sluzby.id_osoby
inner join typy on typy.id_typu = sluzby.id_typu
inner join sprawozdania on sprawozdania.id_sluzby = sluzby.id_sluzby
where sluzby.id_sluzby = (select sluzby.id_sluzby from sluzby where id_osoby = osoba and id_typu = typ and kiedy_sluzba=kiedy and id_uzytkownika = uzytkownik);
END$$

DROP PROCEDURE IF EXISTS `DodajNowaSluzbeFunkcja`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `DodajNowaSluzbeFunkcja` (`kto` INT, `typ` INT, `kiedy` DATETIME, `uzytkownik` INT)  BEGIN
INSERT INTO sluzby VALUES (NULL, kto, typ, kiedy, CURRENT_TIMESTAMP(), uzytkownik, 'id_gcal');
END$$

DROP PROCEDURE IF EXISTS `DodajOsobe`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `DodajOsobe` (`nazwiskoPHP` VARCHAR(100), `imiePHP` VARCHAR(100))  BEGIN
INSERT INTO `osoby`
(`id_osoby`,
`nazwisko`,
`imie`,
`aktywny`)
VALUES
(null,
nazwiskoPHP,
imiePHP,
'1');
END$$

DROP PROCEDURE IF EXISTS `DodajSprawozdanieDomyslne`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `DodajSprawozdanieDomyslne` (`sluzba` INT)  BEGIN
INSERT INTO sprawozdania
(`id_sprawozdania`,
`id_sluzby`,
`publikacje`,
`filmy`,
`godziny`,
`odwiedziny`,
`studia`)
VALUES
(NULL,sluzba,'0','0','0','0','0');
END$$

DROP PROCEDURE IF EXISTS `ListaOsobAktywnych`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `ListaOsobAktywnych` ()  BEGIN
select id_osoby, concat(osoby.nazwisko, ' ', osoby.imie) as kto
from osoby 
where osoby.aktywny = "1"
order by osoby.nazwisko, osoby.imie;
END$$

DROP PROCEDURE IF EXISTS `ListaOsobStatystyczna`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `ListaOsobStatystyczna` ()  BEGIN
select osoby.id_osoby, osoby.nazwisko, osoby.imie
from sluzby
right join osoby on sluzby.id_osoby = osoby.id_osoby
where aktywny = 1
group by osoby.id_osoby
order by osoby.nazwisko, osoby.imie;
END$$

DROP PROCEDURE IF EXISTS `LogAdd`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `LogAdd` (`id_user` INT, `contents` VARCHAR(500), `ip` VARCHAR(15))  BEGIN
INSERT INTO `logs` (`id_log`,`id_uzytkownika`,`log`,`ip`,`time`) VALUES(NULL,id_user,contents,ip,current_timestamp());
END$$

DROP PROCEDURE IF EXISTS `ProfileLista`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `ProfileLista` ()  BEGIN
SELECT *, concat (nazwa, ' (', TIME_FORMAT(kwantum,"%H"),'h)') as pelna_nazwa_celu FROM cele;
END$$

DROP PROCEDURE IF EXISTS `SprawozdaniaLista`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `SprawozdaniaLista` (`uzytkownik` INT, `miesiac` INT, `rok` INT)  BEGIN
SELECT *, concat(osoby.imie, ' ', osoby.nazwisko) as kto, concat(nazwa_typu, ' (', TIME_FORMAT(czas_trwania, "%H:%i"),')') as nazwa_typu,
TIME_FORMAT(sec_to_time(time_to_sec(godziny) - time_to_sec(czas_trwania)), "%H:%i") as bilans_oczekiwania_rzeczywistosc,
TIME_FORMAT(godziny, "%H:%i") as godziny,
SUBSTRING(kiedy_sluzba, 1, 16) as kiedy_sluzba
FROM sprawozdania 
inner join sluzby on sprawozdania.id_sluzby = sluzby.id_sluzby 
inner join osoby on sluzby.id_osoby = osoby.id_osoby
inner join typy on sluzby.id_typu = typy.id_typu
where sluzby.id_uzytkownika = uzytkownik and sprawozdania.id_sluzby in (select sluzby.id_sluzby from sluzby where MONTH(kiedy_sluzba) = miesiac AND YEAR(kiedy_sluzba) = rok) 
order by sluzby.kiedy_sluzba;
END$$

DROP PROCEDURE IF EXISTS `SprawozdaniaSuma`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `SprawozdaniaSuma` (`uzytkownik` INT, `miesiac` INT, `rok` INT)  BEGIN
select 
sum(publikacje) as s_publikacje,
sum(filmy) as s_filmy,
TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny))), "%H:%i") as s_godziny,
TIME_FORMAT(sec_to_time(sum(time_to_sec(czas_trwania))), "%H:%i") as s_typy,
TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny))-sum(time_to_sec(czas_trwania))), "%H:%i") as bilans_oczekiwania_rzeczywistosc,
sum(studia) as s_studia,
sum(odwiedziny) as s_odwiedziny,
cele.id_celu,
TIME_FORMAT(sec_to_time(sum(time_to_sec(czas_trwania))-(time_to_sec(kwantum))), "%H:%i") as bilans_stypy_kwantum,
uzytkownicy.id_uzytkownika,
concat (cele.nazwa, ' (', TIME_FORMAT(cele.kwantum,"%H"),'h)') as pelna_nazwa_celu,
TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny)) - time_to_sec(cele.kwantum)), "%H:%i") as roznica_godzin,
TIME_FORMAT(ceil(sec_to_time((sum(time_to_sec(godziny)) - time_to_sec(cele.kwantum)) / (DAYOFMONTH(LAST_DAY(CURRENT_TIMESTAMP())) - DAY(CURRENT_TIMESTAMP())+1)*-1)), "%H:%i") as rzeczywisty_cel_dzienny,
TIME_FORMAT(sec_to_time(sum(time_to_sec(godziny))-ceil(time_to_sec(cele.kwantum) / DAYOFMONTH(LAST_DAY(CURRENT_TIMESTAMP())))*DAY(CURRENT_TIMESTAMP())), "%H:%i") as bilans_rzeczywisty
from sprawozdania
inner join sluzby on sprawozdania.id_sluzby=sluzby.id_sluzby
inner join uzytkownicy on uzytkownicy.id_uzytkownika = sluzby.id_uzytkownika
inner join cele on uzytkownicy.id_celu = cele.id_celu
inner join typy on sluzby.id_typu = typy.id_typu
where sluzby.id_uzytkownika = uzytkownik and sprawozdania.id_sluzby in (select sluzby.id_sluzby from sluzby where MONTH(kiedy_sluzba) = miesiac AND YEAR(kiedy_sluzba) = rok);
END$$

DROP PROCEDURE IF EXISTS `StatystykaOsobowa`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `StatystykaOsobowa` (`uzytkownik` INT, `miesiac` INT, `rok` INT)  BEGIN
SELECT concat(osoby.nazwisko, ' ', osoby.imie) as kto, osoby.id_osoby, count(osoby.id_osoby) as ile FROM sprawozdania
inner join sluzby on sprawozdania.id_sluzby=sluzby.id_sluzby
inner join osoby on sluzby.id_osoby = osoby.id_osoby
where sluzby.id_uzytkownika = uzytkownik
and sprawozdania.id_sluzby in (select sluzby.id_sluzby from sluzby where MONTH(kiedy_sluzba) = miesiac AND YEAR(kiedy_sluzba) = rok) 
group by osoby.id_osoby
order by ile desc, osoby.nazwisko asc;
END$$

DROP PROCEDURE IF EXISTS `TelegramKontrola`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `TelegramKontrola` ()  BEGIN
select telegram_chat_id, concat('Wyslano wiadomości o ', current_timestamp()) as tresc
from uzytkownicy where id_uzytkownika = 1;
END$$

DROP PROCEDURE IF EXISTS `TelegramSluzby`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `TelegramSluzby` ()  BEGIN
SELECT telegram_chat_id, DATEDIFF(kiedy_sluzba, current_timestamp()) as dzien,
concat(nazwa_typu, ' z ', osoby.imie, ' ', osoby.nazwisko,' o ', TIME_FORMAT(kiedy_sluzba,"%H:%i")) as tresc
FROM sluzby
inner join osoby on osoby.id_osoby = sluzby.id_osoby
inner join typy on typy.id_typu = sluzby.id_typu
inner join uzytkownicy on uzytkownicy.id_uzytkownika = sluzby.id_uzytkownika
having dzien =1
order by kiedy_sluzba;
END$$

DROP PROCEDURE IF EXISTS `TelegramSprawozdania`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `TelegramSprawozdania` ()  BEGIN
SELECT uzytkownicy.id_osoby, uzytkownicy.id_uzytkownika, telegram_chat_id, 
concat('Sprawozdanie za: ', DATE_FORMAT(current_timestamp(),'%m.%Y'), '\r\nPublikacje: ', sum(publikacje), '\r\nPokazane filmy: ', sum(filmy), '\r\nGodziny: ',TIME_FORMAT(sec_to_time(floor(sum(time_to_sec(godziny)))), '%H') , '\r\nOdwiedziny ponowne: ', sum(odwiedziny),'\r\nStudia biblijne: ', sum(studia)) as tresc_sprawozdania,
concat('Minuty do przeniesienia na następny miesiąc: ', TIME_FORMAT(sec_to_time(floor(sum(time_to_sec(godziny)))), '%i')) as minuty,
DAYOFMONTH(LAST_DAY(CURRENT_TIMESTAMP())) as ostatni, 
DAY(CURRENT_TIMESTAMP()) as dzis,
TIMEDIFF(sec_to_time(sum(time_to_sec(godziny))),sec_to_time((floor(sum(time_to_sec(godziny))/3600))*3600)) as minuty_do_przeniesienia,
concat(DATE_ADD(CURRENT_DATE(), interval 1 day),' 00:00:00') as jutro
FROM sluzby
inner join uzytkownicy on uzytkownicy.id_uzytkownika = sluzby.id_uzytkownika
inner join sprawozdania on sprawozdania.id_sluzby = sluzby.id_sluzby
where telegram_chat_id !=''
and month(sluzby.kiedy_sluzba) = month(CURRENT_DATE())
and year(sluzby.kiedy_sluzba) = year(CURRENT_DATE())
group by telegram_chat_id;
END$$

DROP PROCEDURE IF EXISTS `TelegramToken`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `TelegramToken` ()  BEGIN
select imie as token from osoby where nazwisko = 'SluzbyBot';
END$$

DROP PROCEDURE IF EXISTS `TypyLista`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `TypyLista` ()  BEGIN
select *, concat(nazwa_typu, ' (', TIME_FORMAT(czas_trwania, "%H:%i"), ')') as typ_czas from typy order by czas_trwania;
END$$

DROP PROCEDURE IF EXISTS `UsunSprawozdanieDomyslne`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `UsunSprawozdanieDomyslne` (`sluzba` INT)  BEGIN
DELETE FROM sprawozdania WHERE sprawozdania.id_sluzby=sluzba;
END$$

DROP PROCEDURE IF EXISTS `WyszukiwarkaOsobowa`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `WyszukiwarkaOsobowa` (IN `kto` INT, `uzytkownik` INT)  BEGIN
SELECT sprawozdania.id_sprawozdania, sluzby.id_sluzby, sluzby.id_osoby, SUBSTRING(sluzby.kiedy_sluzba, 1, 16) as kiedy_sluzba, osoby.nazwisko, osoby.imie, typy.id_typu ,typy.nazwa_typu,
datediff(sluzby.kiedy_sluzba, CURRENT_TIMESTAMP) as 'roznica' FROM sluzby
inner join osoby on osoby.id_osoby=sluzby.id_osoby
inner join typy on typy.id_typu = sluzby.id_typu
left join sprawozdania on sprawozdania.id_sluzby=sluzby.id_sluzby
where sluzby.id_osoby=kto and id_uzytkownika = uzytkownik
order by nazwisko asc, imie asc, kiedy_sluzba desc;
END$$

DROP PROCEDURE IF EXISTS `WyszukiwarkaSluzbyAll`$$
CREATE DEFINER=`swiktor`@`%` PROCEDURE `WyszukiwarkaSluzbyAll` (`uzytkownik` INT)  BEGIN
SELECT id_sluzby, sluzby.id_osoby, SUBSTRING((max(sluzby.kiedy_sluzba)), 1, 16) as kiedy_sluzba, 
osoby.nazwisko, osoby.imie, typy.id_typu ,typy.nazwa_typu, 
datediff((max(sluzby.kiedy_sluzba)), CURRENT_TIMESTAMP) as 'roznica' FROM sluzby
inner join osoby on osoby.id_osoby=sluzby.id_osoby
inner join typy on typy.id_typu = sluzby.id_typu
where osoby.aktywny = "1" and id_uzytkownika = uzytkownik
group by id_osoby
order by kiedy_sluzba asc, nazwisko asc, imie asc;
END$$

DELIMITER ;

DROP TABLE IF EXISTS `cele`;
CREATE TABLE `cele` (
  `id_celu` int(11) NOT NULL,
  `nazwa` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `kwantum` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `osoby`;
CREATE TABLE `osoby` (
  `id_osoby` int(11) NOT NULL,
  `nazwisko` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `imie` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `aktywny` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `sluzby`;
CREATE TABLE `sluzby` (
  `id_sluzby` int(11) NOT NULL,
  `id_osoby` int(11) DEFAULT NULL,
  `id_typu` int(11) DEFAULT NULL,
  `kiedy_sluzba` datetime NOT NULL,
  `kiedy_wpis` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_uzytkownika` int(11) DEFAULT NULL,
  `id_gcal` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
DROP TRIGGER IF EXISTS `DodajSprawozdanie`;
DELIMITER $$
CREATE TRIGGER `DodajSprawozdanie` AFTER INSERT ON `sluzby` FOR EACH ROW call DodajSprawozdanieDomyslne(NEW.id_sluzby)
$$
DELIMITER ;

DROP TABLE IF EXISTS `sprawozdania`;
CREATE TABLE `sprawozdania` (
  `id_sprawozdania` int(11) NOT NULL,
  `id_sluzby` int(11) DEFAULT NULL,
  `publikacje` int(11) DEFAULT '0',
  `filmy` int(11) DEFAULT '0',
  `godziny` time DEFAULT '00:00:00',
  `odwiedziny` int(11) DEFAULT '0',
  `studia` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `typy`;
CREATE TABLE `typy` (
  `id_typu` int(11) NOT NULL,
  `nazwa_typu` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `czas_trwania` time DEFAULT '01:30:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `uzytkownicy`;
CREATE TABLE `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `GAuth` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `haslo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `GCalendar` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `id_celu` int(11) DEFAULT '1',
  `telegram_chat_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_osoby` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `cele`
  ADD PRIMARY KEY (`id_celu`);

ALTER TABLE `osoby`
  ADD PRIMARY KEY (`id_osoby`),
  ADD UNIQUE KEY `id_osoby` (`id_osoby`);

ALTER TABLE `sluzby`
  ADD PRIMARY KEY (`id_sluzby`),
  ADD UNIQUE KEY `id_sluzby` (`id_sluzby`),
  ADD KEY `id_osoby_sluzba_fk` (`id_osoby`),
  ADD KEY `id_typu_sluzba_fk` (`id_typu`),
  ADD KEY `id_uzytkownika_sluzba_fk` (`id_uzytkownika`);

ALTER TABLE `sprawozdania`
  ADD PRIMARY KEY (`id_sprawozdania`),
  ADD UNIQUE KEY `id_sprawozdania` (`id_sprawozdania`),
  ADD KEY `id_sluzby_sprawozdania_fk` (`id_sluzby`);

ALTER TABLE `typy`
  ADD PRIMARY KEY (`id_typu`),
  ADD UNIQUE KEY `id_typu` (`id_typu`),
  ADD UNIQUE KEY `nazwa_typu` (`nazwa_typu`);

ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id_uzytkownika`),
  ADD UNIQUE KEY `id_uzytkownika` (`id_uzytkownika`),
  ADD UNIQUE KEY `nazwa` (`nazwa`),
  ADD KEY `id_celu_uzytkownicy_fk` (`id_celu`),
  ADD KEY `id_osoby_sluzba_fk` (`id_osoby`);


ALTER TABLE `cele`
  MODIFY `id_celu` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `osoby`
  MODIFY `id_osoby` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sluzby`
  MODIFY `id_sluzby` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sprawozdania`
  MODIFY `id_sprawozdania` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `typy`
  MODIFY `id_typu` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `sluzby`
  ADD CONSTRAINT `id_osoby_sluzba_fk` FOREIGN KEY (`id_osoby`) REFERENCES `osoby` (`id_osoby`),
  ADD CONSTRAINT `id_typu_sluzby_fk` FOREIGN KEY (`id_typu`) REFERENCES `typy` (`id_typu`),
  ADD CONSTRAINT `id_uzytkownika_sluzba_fk` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

ALTER TABLE `sprawozdania`
  ADD CONSTRAINT `id_sluzby_sprawozdania_fk` FOREIGN KEY (`id_sluzby`) REFERENCES `sluzby` (`id_sluzby`) ON DELETE CASCADE;

ALTER TABLE `uzytkownicy`
  ADD CONSTRAINT `id_celu_uzytkownicy_fk` FOREIGN KEY (`id_celu`) REFERENCES `cele` (`id_celu`),
  ADD CONSTRAINT `id_osoby_uzytkownicy_fk` FOREIGN KEY (`id_osoby`) REFERENCES `osoby` (`id_osoby`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
