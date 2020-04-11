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
-- Baza danych: `familytree3`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `families`
--

CREATE TABLE `families` (
  `fam_id` int(11) NOT NULL,
  `father` int(11) NOT NULL,
  `mother` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `individuals`
--

CREATE TABLE `individuals` (
  `ind_id` int(11) NOT NULL,
  `first_name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `last_name` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `family` int(11) NOT NULL,
  `gender_mf` enum('m','f') CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`fam_id`),
  ADD KEY `father` (`father`),
  ADD KEY `mother` (`mother`);

--
-- Indeksy dla tabeli `individuals`
--
ALTER TABLE `individuals`
  ADD PRIMARY KEY (`ind_id`),
  ADD KEY `ind_id` (`ind_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `families`
--
ALTER TABLE `families`
  MODIFY `fam_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `individuals`
--
ALTER TABLE `individuals`
  MODIFY `ind_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `families`
--
ALTER TABLE `families`
  ADD CONSTRAINT `families_ibfk_2` FOREIGN KEY (`mother`) REFERENCES `individuals` (`ind_id`),
  ADD CONSTRAINT `families_ibfk_3` FOREIGN KEY (`father`) REFERENCES `individuals` (`ind_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
