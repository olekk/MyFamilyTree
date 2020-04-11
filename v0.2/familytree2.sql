-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 11 Kwi 2020, 23:49
-- Wersja serwera: 10.1.34-MariaDB
-- Wersja PHP: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `familytree2`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `individuals`
--

CREATE TABLE `individuals` (
  `ind_id` int(11) NOT NULL,
  `first_name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `last_name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `gender_mf` enum('m','f') CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `individuals`
--

INSERT INTO `individuals` (`ind_id`, `first_name`, `last_name`, `gender_mf`, `birthdate`) VALUES
(1, 'Imie', 'Nazwisko', 'm', '2020-03-22'),
(2, 'Tata', 'Nazwisko', 'm', '2020-03-22'),
(3, 'Żona', 'Nazwisko', 'f', '2020-03-22'),
(4, 'Żona', 'Nazwisko', 'f', '2020-03-22'),
(5, 'Dziecko', 'Nazwisko', 'm', '2020-03-22');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `relationships`
--

CREATE TABLE `relationships` (
  `rel_id` int(11) NOT NULL,
  `ind1_id` int(11) NOT NULL,
  `ind2_id` int(11) NOT NULL,
  `ind1_role` text COLLATE utf8_unicode_ci NOT NULL,
  `ind2_role` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `relationships`
--

INSERT INTO `relationships` (`rel_id`, `ind1_id`, `ind2_id`, `ind1_role`, `ind2_role`) VALUES
(1, 1, 2, 'Son', 'Father'),
(2, 2, 3, 'Husband', 'Wife'),
(3, 1, 3, 'Son', 'Mother'),
(4, 1, 4, 'Husband', 'Wife'),
(5, 5, 1, 'Son', 'Father'),
(6, 5, 4, 'Son', 'Mother');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `individuals`
--
ALTER TABLE `individuals`
  ADD PRIMARY KEY (`ind_id`),
  ADD KEY `ind_id` (`ind_id`);

--
-- Indeksy dla tabeli `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`rel_id`),
  ADD KEY `ind1_id` (`ind1_id`),
  ADD KEY `ind2_id` (`ind2_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `individuals`
--
ALTER TABLE `individuals`
  MODIFY `ind_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `relationships`
--
ALTER TABLE `relationships`
  MODIFY `rel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `relationships`
--
ALTER TABLE `relationships`
  ADD CONSTRAINT `relationships_ibfk_1` FOREIGN KEY (`ind1_id`) REFERENCES `individuals` (`ind_id`),
  ADD CONSTRAINT `relationships_ibfk_2` FOREIGN KEY (`ind2_id`) REFERENCES `individuals` (`ind_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
