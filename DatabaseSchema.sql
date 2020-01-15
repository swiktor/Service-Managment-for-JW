SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `jw`
--
CREATE DATABASE IF NOT EXISTS `jw` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `jw`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `osoby`
--

CREATE TABLE `osoby` (
  `id_osoby` int(11) NOT NULL,
  `nazwisko` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `imie` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `aktywny` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sluzby`
--

CREATE TABLE `sluzby` (
  `id_sluzby` int(11) NOT NULL,
  `id_osoby` int(11) DEFAULT NULL,
  `id_typu` int(11) DEFAULT NULL,
  `kiedy_sluzba` datetime NOT NULL,
  `kiedy_wpis` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_uzytkownika` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typy`
--

CREATE TABLE `typy` (
  `id_typu` int(11) NOT NULL,
  `nazwa_typu` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `czas_trwania` time DEFAULT '01:30:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL,
  `imie` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nazwisko` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `haslo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `GAuth` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `GCalendar` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `osoby`
--
ALTER TABLE `osoby`
  ADD PRIMARY KEY (`id_osoby`),
  ADD UNIQUE KEY `id_osoby` (`id_osoby`);

--
-- Indeksy dla tabeli `sluzby`
--
ALTER TABLE `sluzby`
  ADD PRIMARY KEY (`id_sluzby`),
  ADD UNIQUE KEY `id_sluzby` (`id_sluzby`),
  ADD KEY `id_osoby_sluzba_fk` (`id_osoby`),
  ADD KEY `id_typu_sluzba_fk` (`id_typu`),
  ADD KEY `id_uzytkownika_sluzba_fk` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `typy`
--
ALTER TABLE `typy`
  ADD PRIMARY KEY (`id_typu`),
  ADD UNIQUE KEY `id_typu` (`id_typu`),
  ADD UNIQUE KEY `nazwa_typu` (`nazwa_typu`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id_uzytkownika`),
  ADD UNIQUE KEY `id_uzytkownika` (`id_uzytkownika`),
  ADD UNIQUE KEY `nazwa` (`nazwa`);

--
-- AUTO_INCREMENT dla tabel zrzutów
--

--
-- AUTO_INCREMENT dla tabeli `osoby`
--
ALTER TABLE `osoby`
  MODIFY `id_osoby` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `sluzby`
--
ALTER TABLE `sluzby`
  MODIFY `id_sluzby` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `typy`
--
ALTER TABLE `typy`
  MODIFY `id_typu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `sluzby`
--
ALTER TABLE `sluzby`
  ADD CONSTRAINT `id_osoby_sluzba_fk` FOREIGN KEY (`id_osoby`) REFERENCES `osoby` (`id_osoby`),
  ADD CONSTRAINT `id_typu_sluzby_fk` FOREIGN KEY (`id_typu`) REFERENCES `typy` (`id_typu`),
  ADD CONSTRAINT `id_uzytkownika_sluzba_fk` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id_uzytkownika`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
