-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 01. Mrz 2025 um 09:37
-- Server-Version: 10.6.18-MariaDB-0ubuntu0.22.04.1
-- PHP-Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `thilo123`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Anfragen`
--

CREATE TABLE `Anfragen` (
  `Ersteller` text NOT NULL,
  `Absender` text NOT NULL,
  `Passwort` bigint(20) NOT NULL,
  `Listenname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Aufgaben`
--

CREATE TABLE `Aufgaben` (
  `IDListe` int(11) NOT NULL,
  `Inhalt` text NOT NULL,
  `Wichtigkeit` text NOT NULL,
  `IDValue` int(11) NOT NULL,
  `Ersteller` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Aufrufe`
--

CREATE TABLE `Aufrufe` (
  `Aufrufe` int(11) NOT NULL,
  `ID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `Aufrufe`
--

INSERT INTO `Aufrufe` (`Aufrufe`, `ID`) VALUES
(2064, ''),
(2064, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Einkaufslistennamen`
--

CREATE TABLE `Einkaufslistennamen` (
  `Benutzername` varchar(100) NOT NULL,
  `Listenname` varchar(100) NOT NULL,
  `Bild` varchar(200) NOT NULL,
  `Typ` varchar(50) NOT NULL,
  `ID` int(11) NOT NULL,
  `Code` bigint(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `Einkaufslistennamen`
--

INSERT INTO `Einkaufslistennamen` (`Benutzername`, `Listenname`, `Bild`, `Typ`, `ID`, `Code`) VALUES
('mama123', 'Lidl', 'shopping-cart-1901584_960_720.webp', 'Online', 37, 1066184770),
('Stefan', 'aaaaaaaaaa', 'abstract-1299319_1280.png', 'Lokal', 40, NULL),
('Stefan', 'aldi', 'euro-145386_1280.png', 'Online', 41, 8493163493),
('Stefan', 'a', 'shopping-cart-1901584_960_720.webp', 'Online', 45, 4062038448),
('Stefan', 'Dammm', 'smiley-39984_1280.png', 'Online', 51, 8093194758),
('Nico123', 'Baum', 'euro-145386_1280.png', 'Online', 52, 7705270217),
('mama123', 'Rewe', 'euro-145386_1280.png', 'Online', 53, 2762343570),
('mama123', 'Penny', 'abstract-1299319_1280.png', 'Online', 54, 1843491627),
('Stefan', 'Lidlonline', 'shopping-cart-1901584_960_720.webp', 'Online', 55, 4993028293),
('Stefan', 'Globus', 'shopping-cart-1901584_960_720.webp', 'Online', 57, 3625936140);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Konto`
--

CREATE TABLE `Konto` (
  `ID` int(11) NOT NULL,
  `Benutzername` varchar(100) NOT NULL,
  `Passwort` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `Konto`
--

INSERT INTO `Konto` (`ID`, `Benutzername`, `Passwort`) VALUES
(5, 'lasse', '$2y$10$4yx9ow1a/iXJ3TiD4bktkOhfmqYfLs4vWZe135WNXyB3Nlx2p9fZ2'),
(8, 'thilo', '$2y$10$0K9s8dYMynafSA3TTPHvvOlIdxdzLGNTMF/TItojiaYe.EM6Pj/oW'),
(9, 'Mama', '$2y$10$sry0GO79S812mopQP5xgA.SkJuvdjnDQzVidU2k8IEt6YWdsP3SHi'),
(14, 'nico', '$2y$10$a2cANxt/8DmmNdhMksOU1.joJRfGT2As8ZDIQIwZ7/dozt9WbjPom'),
(15, 'mama123', '$2y$10$Q/q0jXF9s6qVZpdHiFCGpOgApuAycg7QovHYsxyJalxk3q3yCciQ.'),
(16, 'Stefan', '$2y$10$coIsXL2/raXIHADe7SYP3OPvvnFEUgGInAOTxH2H0y/0MNkKf22Dy'),
(18, 'Nico123', '$2y$10$eDPTIsvLcuYX/14qVGBpf.IPBgUTxZJ.iLNWjzGHkoVHXUte8ea3.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Verknuepfungen`
--

CREATE TABLE `Verknuepfungen` (
  `ID` int(20) NOT NULL,
  `User` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `Verknuepfungen`
--

INSERT INTO `Verknuepfungen` (`ID`, `User`) VALUES
(61, 'Stefan');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Aufgaben`
--
ALTER TABLE `Aufgaben`
  ADD PRIMARY KEY (`IDValue`);

--
-- Indizes für die Tabelle `Einkaufslistennamen`
--
ALTER TABLE `Einkaufslistennamen`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Code` (`Code`);

--
-- Indizes für die Tabelle `Konto`
--
ALTER TABLE `Konto`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Benutzername` (`Benutzername`),
  ADD UNIQUE KEY `ID` (`ID`),
  ADD UNIQUE KEY `Benutzername_2` (`Benutzername`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Aufgaben`
--
ALTER TABLE `Aufgaben`
  MODIFY `IDValue` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT für Tabelle `Einkaufslistennamen`
--
ALTER TABLE `Einkaufslistennamen`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT für Tabelle `Konto`
--
ALTER TABLE `Konto`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
