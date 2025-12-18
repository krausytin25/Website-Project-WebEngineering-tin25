# Website-Project-WebEngineering-tin25

---

## 📌 Projektbeschreibung

Dieses Projekt ist eine vollständige Webanwendung für einen Sportverein (TSV Dierfeld).  
Die Website dient sowohl als **öffentliche Informationsplattform** als auch als **interner Mitgliederbereich** mit Rollen- und Rechtesystem.

Besucher können sich über den Verein informieren, während registrierte Mitglieder Zugriff auf ein persönliches Profil erhalten.  
Berechtigte Personen (z. B. Abteilungsleiter) können zusätzlich **News und Termine verwalten**.

---

## 🧰 Verwendete Technologien

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

## 🛠️ Setup-Anleitung

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
` $dsn    = 'mysql:host=127.0.0.1;dbname=tsvDierfeld;charset=utf8mb4';
  $dbUser = 'root';
  $dbPass = '';`
