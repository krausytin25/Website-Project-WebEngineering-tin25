<?php
session_start();

/** @var PDO $pdo */
require_once __DIR__ . '/inc/db.php';

// Wenn nicht eingeloggt → zurück zur Anmeldung
if (empty($_SESSION['loggedIn']) || empty($_SESSION['email'])) {
    header('Location: registration.php');
    exit;
}

$email = $_SESSION['email'];

// Benutzer aus DB laden
$stmt = $pdo->prepare('SELECT * FROM benutzer WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Falls der Benutzer in der DB nicht (mehr) existiert
    $displayName = 'Unbekannter Benutzer';
    $initials    = '??';
    $roleText    = 'Mitglied';
    $emailDisplay = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $telefon = '–';
    $adresse = '–';
    $eintritt = '–';
    $mitgliedsnummer = '–';
} else {

    // Helfer-Funktion
    $h = function ($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    };

    $vorname  = isset($user['vorname']) ? $user['vorname'] : '';
    $nachname = isset($user['nachname']) ? $user['nachname'] : '';
    $displayName = trim($vorname . ' ' . $nachname);

    // Initialen für Avatar
    $initials = '';
    if ($vorname !== '') {
        $initials .= mb_strtoupper(mb_substr($vorname, 0, 1));
    }
    if ($nachname !== '') {
        $initials .= mb_strtoupper(mb_substr($nachname, 0, 1));
    }
    if ($initials === '') {
        $initials = '??';
    }

    // Mitgliedsart lesbar machen
    $tarifText = 'Mitglied';
    switch (isset($user['mitgliedstarif']) ? $user['mitgliedstarif'] : '') {
        case 'kinder':
            $tarifText = 'Mitglied · Kinder & Jugendliche';
            break;
        case 'erwachsene':
            $tarifText = 'Mitglied · Erwachsene';
            break;
        case 'ermaessigt':
            $tarifText = 'Mitglied · Ermäßigt';
            break;
        case 'familie':
            $tarifText = 'Mitglied · Familienmitgliedschaft';
            break;
        default:
            $tarifText = 'Mitglied';
    }
    $roleText = $tarifText;

    // Kontakt & Adresse
    $emailDisplay = $h(isset($user['email']) ? $user['email'] : '');
    $mobil        = isset($user['mobil']) ? $user['mobil'] : '';
    $telefon      = isset($user['telefon']) ? $user['telefon'] : '';

    $adresseParts = [];
    if (!empty($user['strasse']) || !empty($user['hausnummer'])) {
        $adresseParts[] = trim((isset($user['strasse']) ? $user['strasse'] : '') . ' ' . (isset($user['hausnummer']) ? $user['hausnummer'] : ''));
    }
    if (!empty($user['plz']) || !empty($user['ort'])) {
        $adresseParts[] = trim((isset($user['plz']) ? $user['plz'] : '') . ' ' . (isset($user['ort']) ? $user['ort'] : ''));
    }
    if (!empty($user['land'])) {
        $adresseParts[] = $user['land'];
    }
    $adresse = $adresseParts ? implode(', ', $adresseParts) : '–';

    // Eintrittsdatum formatieren
    if (!empty($user['eintrittsdatum'])) {
        try {
            $eintrittObj = new DateTime($user['eintrittsdatum']);
            $eintritt = $eintrittObj->format('d.m.Y');
        } catch (Exception $e) {
            $eintritt = $user['eintrittsdatum'];
        }
    } else {
        $eintritt = '–';
    }

    // Mitgliedsnummer
    $mitgliedsnummer = !empty($user['mitgliedsnummer'])
            ? $user['mitgliedsnummer']
            : '–';

    // Geburtstag schön formatieren
    if (!empty($user['geburtstag'])) {
        try {
            $geburtsdatumObj = new DateTime($user['geburtstag']);
            $geburtsdatumFormatiert = $geburtsdatumObj->format('d.m.Y');
        } catch (Exception $e) {
            $geburtsdatumFormatiert = $user['geburtstag'];
        }
    } else {
        $geburtsdatumFormatiert = '–';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profil</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <link rel="stylesheet" href="../assets/css/breadcrumb.css">
</head>
<body>
<div id="header"></div>

<nav class="breadcrumbs">
    <a href="index.php">Startseite</a>
    <span class="sep">/</span>
    <a href="registration.php">Anmeldung</a>
    <span class="sep">/</span>
    <span class="current">Profil</span>
</nav>

<main class="profile-page">
    <section class="profile-card">
        <div class="profile-card__header">
            <div class="profile-avatar">
                <span class="profile-avatar__initials">
                    <?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>
            <div class="profile-main-info">
                <h1><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="profile-role"><?= htmlspecialchars($roleText, ENT_QUOTES, 'UTF-8') ?></p>

                <div class="profile-badges">
                    <!-- Hier könntest du später dynamische Badges einbauen -->
                    <span class="badge badge--terracotta">Mitglied</span>
                    <?php if (!empty($user['mitgliedstarif'])): ?>
                        <span class="badge badge--terracotta">
                            Tarif: <?= htmlspecialchars($user['mitgliedstarif'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-card__body">
            <div class="profile-layout">
                <!-- Linke Spalte: Stammdaten -->
                <section class="profile-section">
                    <h2>Persönliche Daten</h2>
                    <dl class="profile-data">
                        <div class="profile-data__row">
                            <dt>E-Mail</dt>
                            <dd><?= $emailDisplay ?></dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Mobil</dt>
                            <dd><?= !empty($mobil) ? htmlspecialchars($mobil, ENT_QUOTES, 'UTF-8') : '–' ?></dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Telefon</dt>
                            <dd><?= !empty($telefon) ? htmlspecialchars($telefon, ENT_QUOTES, 'UTF-8') : '–' ?></dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Geburtstag</dt>
                            <dd><?= htmlspecialchars($geburtsdatumFormatiert, ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Adresse</dt>
                            <dd><?= htmlspecialchars($adresse, ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Eintrittsdatum</dt>
                            <dd><?= htmlspecialchars($eintritt, ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Mitgliedsnummer</dt>
                            <dd><?= htmlspecialchars($mitgliedsnummer, ENT_QUOTES, 'UTF-8') ?></dd>
                        </div>
                    </dl>
                </section>

                <!-- Rechte Spalte: Vereinsinfos & Aktivitäten -->
                <section class="profile-section">
                    <h2>Im Verein aktiv</h2>
                    <ul class="profile-activity-list">
                        <li>
                            <h3>Rolle im Verein</h3>
                            <p>Diese Informationen können später aus weiteren Feldern oder einer eigenen Tabelle geladen werden.</p>
                        </li>
                        <li>
                            <h3>Teams & Trainingszeiten</h3>
                            <p>Hier könntest du z.B. Mannschaft und Trainingszeiten hinterlegen.</p>
                        </li>
                        <li>
                            <h3>Ehrenamtliche Aufgaben</h3>
                            <p>Noch keine Angaben hinterlegt.</p>
                        </li>
                    </ul>

                    <h2>Statistik</h2>
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <span class="profile-stat__number">0</span>
                            <span class="profile-stat__label">Spiele</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat__number">0</span>
                            <span class="profile-stat__label">Tore</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat__number">0</span>
                            <span class="profile-stat__label">Einsätze als Helfer</span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="#">
                            <button class="button primary btn--action" type="button">Profil bearbeiten</button>
                        </a>
                        <a href="addNews.php">
                            <button class="button primary btn--action" type="button">News oder Termine hinzufügen</button>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>
<div id="footer"></div>
<script src="../assets/js/header.js"></script>
<script src="../assets/js/footer.js"></script>
</body>
</html>
