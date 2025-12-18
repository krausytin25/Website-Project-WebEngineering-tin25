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

Weitere Informationen befinden sich im Wiki:

https://taiga-dhhdhai-u11685.vm.elestio.app/project/vereinswebsite/wiki/projektdefinition

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
[Beschreibung_Lokale_Instanz.docx](https://github.com/user-attachments/files/24241434/Beschreibung_Lokale_Instanz.docx)

### 1️⃣ Voraussetzungen
- PHP 8.x
- MySQL / MariaDB
- Webserver (Apache / Nginx / XAMPP)

### 2️⃣ Projekt einrichten
1. Repository klonen oder Dateien kopieren
2. Projekt in das Webserver-Verzeichnis legen
3. Datenbank anlegen

Schritt für Schritt Anleitung für Projekt ins Verzeichnis legen:

Falls bisher nichts geändert wurde, muss hier die ZIP entpackt werden. Dann über localhost + Pfad drauf zugreifen:
Bsp.: localhost\Website-Project-WebEngineering-tin25-main\src\index.php

<img width="463" height="268" alt="image" src="https://github.com/user-attachments/assets/bbcee903-c3ad-4b35-b67a-c76b27f1911e" />


### 3️⃣ Datenbank
- SQL-Dump importieren
- In inc/db.php Zugangsdaten anpassen:
` $dsn    = 'mysql:host=127.0.0.1;dbname=tsvDierfeld;charset=utf8mb4'; `
` $dbUser = 'root'; `
` $dbPass = ''; `

DB DUMP als SQL:
[tsvdierfeld.sql](https://github.com/user-attachments/files/24241130/tsvdierfeld.sql)

DB DUMP als YML:
[tsvdierfeld.yml](https://github.com/user-attachments/files/24241137/tsvdierfeld.yml)

*Schritt für Schritt Anleitung für Datenbankimport:*

1. XAMPP installieren und starten:

<img width="494" height="320" alt="image" src="https://github.com/user-attachments/assets/3d0ec28b-7b61-43a7-bd71-429d81474614" />

2. [localhost/phpmyadmin](http://localhost/phpmyadmin/) aufrufen, neue DB hinzufügen mit dem Namen  `tsvdierfeld`
<img width="555" height="199" alt="image" src="https://github.com/user-attachments/assets/9af39704-93e4-46dd-bfe2-4e4b77f3784c" />

3. Datenbank importieren:
   1. Erstellte Datenbank auswählen
   2. `Importieren`auswählen
   3. Datenbank Dump auswählen und hochladen -> Datenbank ist importiert.
<img width="922" height="299" alt="image" src="https://github.com/user-attachments/assets/7e676162-dc18-4fb8-9a99-a2f7ce335cde" />









