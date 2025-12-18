# Website-Project-WebEngineering-tin25
- [1. Projektbeschreibung](#1-projektbeschreibung)
- [2. Verwendete Technologien](#2-verwendete-technologien)
  - [Backend](#backend)
  - [Frontend](#frontend)
  - [Sonstiges](#sonstiges)
- [3. Setup-Anleitung](#3-setup-anleitung)




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
- JavaScript

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

DB DUMP als SQL:
[tsvdierfeld.sql](https://github.com/user-attachments/files/24240873/tsvdierfeld.sql)

DB DUMP als YML:
[tsvdierfeld.yml](https://github.com/user-attachments/files/24240905/tsvdierfeld.yml)


Schritt für Schritt Anleitung für XAMPP:

1. XAMPP installieren und starten:

<img width="494" height="320" alt="image" src="https://github.com/user-attachments/assets/3d0ec28b-7b61-43a7-bd71-429d81474614" />



