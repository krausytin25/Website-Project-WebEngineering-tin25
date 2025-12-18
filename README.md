# Website-Project-WebEngineering-tin25
[1. Projektbeschreibung](#1-projektbeschreibung)

[2. Verwendete Technologien](#2-verwendete-technologien)

[3. Setup-Anleitung](#3-setup-anleitung)




---

## 1. Projektbeschreibung

Dieses Projekt ist eine vollständige Webanwendung für einen Sportverein (TSV Dierfeld).  
Die Website dient sowohl als **öffentliche Informationsplattform** als auch als **interner Mitgliederbereich** mit Rollen- und Rechtesystem.

Besucher können sich über den Verein informieren, während registrierte Mitglieder Zugriff auf ein persönliches Profil erhalten.  
Berechtigte Personen (z. B. Abteilungsleiter) können zusätzlich **News und Termine verwalten**.

---

## 2. Verwendete Technologien

### Backend
- PHP 8+
- MySQL / MariaDB
- PDO

### Frontend
- HTML5
- CSS3
- JavaScript (Vanilla)

### Sonstiges
- Sessions & Cookies
- Modularer Include-Aufbau
- Responsive Design

## 3. Setup-Anleitung

### 1️⃣ Voraussetzungen
- PHP 8.x
- MySQL / MariaDB
- Webserver (Apache / Nginx / XAMPP)

### 2️⃣ Projekt einrichten
1. Repository klonen oder Dateien kopieren
2. Projekt in das Webserver-Verzeichnis legen
3. Datenbank anlegen

### 3️⃣ Datenbank
- SQL-Dump importieren
- In inc/db.php Zugangsdaten anpassen:
` $dsn    = 'mysql:host=127.0.0.1;dbname=tsvDierfeld;charset=utf8mb4'; `
` $dbUser = 'root'; `
` $dbPass = ''; `


[tsvdierfeld.sql](https://github.com/user-attachments/files/24240873/tsvdierfeld.sql)

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Dez 2025 um 17:50
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `tsvdierfeld`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nachname` varchar(255) DEFAULT NULL,
  `vorname` varchar(100) NOT NULL,
  `anrede` varchar(20) DEFAULT NULL,
  `geburtstag` date DEFAULT NULL,
  `mobil` varchar(50) DEFAULT NULL,
  `telefon` varchar(50) DEFAULT NULL,
  `passwort` varchar(255) NOT NULL,
  `mitgliedstarif` varchar(50) DEFAULT NULL,
  `strasse` varchar(255) DEFAULT NULL,
  `hausnummer` varchar(20) DEFAULT NULL,
  `plz` varchar(20) DEFAULT NULL,
  `ort` varchar(100) DEFAULT NULL,
  `land` varchar(100) DEFAULT NULL,
  `rolle_verein` varchar(255) DEFAULT NULL,
  `team_info` varchar(255) DEFAULT NULL,
  `ehrenamt` varchar(255) DEFAULT NULL,
  `spiele` int(10) UNSIGNED DEFAULT NULL,
  `tore` int(10) UNSIGNED DEFAULT NULL,
  `helfer_einsaetze` int(10) UNSIGNED DEFAULT NULL,
  `eintrittsdatum` date NOT NULL DEFAULT curdate(),
  `mitgliedsnummer` varchar(20) DEFAULT NULL,
  `can_add_news` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`id`, `email`, `nachname`, `vorname`, `anrede`, `geburtstag`, `mobil`, `telefon`, `passwort`, `mitgliedstarif`, `strasse`, `hausnummer`, `plz`, `ort`, `land`, `rolle_verein`, `team_info`, `ehrenamt`, `spiele`, `tore`, `helfer_einsaetze`, `eintrittsdatum`, `mitgliedsnummer`, `can_add_news`) VALUES
(15, 'test@gmail.com', 'User', 'Test', 'herr', '2002-02-18', '+12345', '+49231331313', '$2y$10$D9idLUHYk7tSxxlxnV..GOLCeES59FQ6iQk3d.fS6qAKZMt7OgytK', 'erwachsene', 'Im Karrenheld', '17', '73466', 'Dierfeld', 'Deutschland', 'Volleyball, Handball, Fußball, Turnen, Spieler, Passiv', 'F-Jugend', 'Sommerfest', 2, 2, 2, '2025-12-18', 'V-0015', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `bild` varchar(255) DEFAULT NULL,
  `beschreibung` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `news`
--

INSERT INTO `news` (`id`, `titel`, `bild`, `beschreibung`) VALUES
(15, 'Es gibt neue Fußbälle', 'uploads/news_693a825f95fa62.65199101.jpg', 'Wir haben neue Fußbälle besorgt.'),
(16, 'TesNews', 'uploads/news_69440fb95c7d34.57989146.png', 'TestNews'),
(17, 'TestNews', 'uploads/news_52319959644618b7936a186c91b82b27.jpg', 'Da schläft einer.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termin`
--

CREATE TABLE `termin` (
  `id` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `datum` date NOT NULL,
  `uhrzeit` time NOT NULL,
  `veranstaltungsort` varchar(255) DEFAULT NULL,
  `beschreibung` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `termin`
--

INSERT INTO `termin` (`id`, `titel`, `datum`, `uhrzeit`, `veranstaltungsort`, `beschreibung`) VALUES
(5, 'Fasching', '2026-01-30', '20:30:00', 'Dierfeld', 'Der jährliche Wild Wild West Fasching'),
(6, 'Bratenfest', '2025-12-20', '10:40:00', 'Bürgersaal', 'Lecker Essen'),
(9, 'TestTermin', '2025-12-26', '17:34:00', 'HDH', 'TestTermin');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `termin`
--
ALTER TABLE `termin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `termin`
--
ALTER TABLE `termin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
