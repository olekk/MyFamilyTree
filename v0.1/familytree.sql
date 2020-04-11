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
-- Baza danych: `familytree`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `family`
--

CREATE TABLE `family` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_polish_ci NOT NULL,
  `father` int(11) NOT NULL,
  `mother` int(11) NOT NULL,
  `gender` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `family`
--

INSERT INTO `family` (`id`, `name`, `father`, `mother`, `gender`) VALUES
(1, 'Olek', 2, 3, 1),
(2, 'Henryk CieÅ›la', 0, 0, 1),
(3, 'Mama', 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `individuals`
--

CREATE TABLE `individuals` (
  `individual_id` int(11) NOT NULL,
  `name` text COLLATE utf8_polish_ci NOT NULL,
  `gender` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `individuals`
--

INSERT INTO `individuals` (`individual_id`, `name`, `gender`) VALUES
(1, 'Imie', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `relations`
--

CREATE TABLE `relations` (
  `relation_id` int(11) NOT NULL,
  `ind1_id` int(11) NOT NULL,
  `ind2_id` int(11) NOT NULL,
  `ind1_role` varchar(10) COLLATE utf8_polish_ci NOT NULL,
  `ind2_role` varchar(10) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `individuals`
--
ALTER TABLE `individuals`
  ADD PRIMARY KEY (`individual_id`);

--
-- Indeksy dla tabeli `relations`
--
ALTER TABLE `relations`
  ADD PRIMARY KEY (`relation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `family`
--
ALTER TABLE `family`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `individuals`
--
ALTER TABLE `individuals`
  MODIFY `individual_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `relations`
--
ALTER TABLE `relations`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
