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

// 1) Edit-Modus? (Wenn die URL ?edit=1 enthält)
$isEditMode = isset($_GET['edit']) && $_GET['edit'] === '1';

/* 2) Profildaten aus rechtem Bereich speichern */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {

    $rolleVerein = isset($_POST['rolle_verein']) ? $_POST['rolle_verein'] : '';
    $teamsZeiten = isset($_POST['teams_trainingszeiten']) ? $_POST['teams_trainingszeiten'] : '';
    $ehrenamt = isset($_POST['ehrenamtliche_aufgaben']) ? $_POST['ehrenamtliche_aufgaben'] : '';
    $spiele = ($_POST['spiele'] !== '') ? (int)$_POST['spiele'] : null;
    $tore = ($_POST['tore'] !== '') ? (int)$_POST['tore'] : null;
    $helferEinsatz = ($_POST['helfer'] !== '') ? (int)$_POST['helfer'] : null;

    $update = $pdo->prepare("
        UPDATE benutzer
        SET
            rolle_verein      = :rolle,
            team_info         = :teams,
            ehrenamt          = :ehrenamt,
            spiele            = :spiele,
            tore              = :tore,
            helfer_einsaetze  = :helfer
        WHERE email = :email
        LIMIT 1
    ");

    $update->execute([
            ':rolle' => $rolleVerein,
            ':teams' => $teamsZeiten,
            ':ehrenamt' => $ehrenamt,
            ':spiele' => $spiele,
            ':tore' => $tore,
            ':helfer' => $helferEinsatz,
            ':email' => $email,
    ]);

    // nach dem Speichern zurück in die normale Ansicht
    header('Location: profile.php');
    exit;
}

// 3) Benutzer aus DB laden
$stmt = $pdo->prepare('SELECT * FROM benutzer WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $displayName = 'Unbekannter Benutzer';
    $initials = '??';
    $roleText = 'Mitglied';
    $emailDisplay = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $telefon = '–';
    $adresse = '–';
    $eintritt = '–';
    $mitgliedsnummer = '–';
    $geburtsdatumFormatiert = '–';
    $canAddNews = false;
} else {

    // Helfer-Funktion
    $h = function ($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    };

    $vorname = isset($user['vorname']) ? $user['vorname'] : '';
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
    $mobil = isset($user['mobil']) ? $user['mobil'] : '';
    $telefon = isset($user['telefon']) ? $user['telefon'] : '';

    $adresseParts = [];
    if (!empty($user['strasse']) || !empty($user['hausnummer'])) {
        $adresseParts[] = trim(
                (isset($user['strasse']) ? $user['strasse'] : '')
                . ' ' .
                (isset($user['hausnummer']) ? $user['hausnummer'] : '')
        );
    }
    if (!empty($user['plz']) || !empty($user['ort'])) {
        $adresseParts[] = trim(
                (isset($user['plz']) ? $user['plz'] : '')
                . ' ' .
                (isset($user['ort']) ? $user['ort'] : '')
        );
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

    // Geburtstag formatieren
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

    // darf News/Termine hinzufügen? (wenn Rolle "Abteilungsleiter" gesetzt ist)
    $canAddNews = false;
    if (!empty($user['rolle_verein'])) {
        $canAddNews = (stripos($user['rolle_verein'], 'abteilungsleiter') !== false);
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

                    <?php if (!$isEditMode): ?>
                        <!-- ANZEIGE-MODUS --------------------------------------->
                        <ul class="profile-activity-list">
                            <li>
                                <h3>Rolle im Verein</h3>
                                <p><?= !empty($user['rolle_verein'])
                                            ? htmlspecialchars($user['rolle_verein'], ENT_QUOTES, 'UTF-8')
                                            : 'Noch keine Angaben hinterlegt.' ?></p>
                            </li>
                            <li>
                                <h3>Teams & Trainingszeiten</h3>
                                <p><?= !empty($user['team_info'])
                                            ? htmlspecialchars($user['team_info'], ENT_QUOTES, 'UTF-8')
                                            : 'Hier könntest du z.B. Mannschaft und Trainingszeiten hinterlegen.' ?></p>
                            </li>
                            <li>
                                <h3>Ehrenamtliche Aufgaben</h3>
                                <p><?= !empty($user['ehrenamt'])
                                            ? htmlspecialchars($user['ehrenamt'], ENT_QUOTES, 'UTF-8')
                                            : 'Noch keine Angaben hinterlegt.' ?></p>
                            </li>
                        </ul>

                        <h2>Statistik</h2>
                        <div class="profile-stats">
                            <div class="profile-stat">
                                <span class="profile-stat__number">
                                    <?= isset($user['spiele']) ? (int)$user['spiele'] : 0 ?>
                                </span>
                                <span class="profile-stat__label">Spiele</span>
                            </div>
                            <div class="profile-stat">
                                <span class="profile-stat__number">
                                    <?= isset($user['tore']) ? (int)$user['tore'] : 0 ?>
                                </span>
                                <span class="profile-stat__label">Tore</span>
                            </div>
                            <div class="profile-stat">
                                <span class="profile-stat__number">
                                    <?= isset($user['helfer_einsaetze']) ? (int)$user['helfer_einsaetze'] : 0 ?>
                                </span>
                                <span class="profile-stat__label">Einsätze als Helfer</span>
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="profile.php?edit=1">
                                <button class="button primary btn--action" type="button">
                                    Profil bearbeiten
                                </button>
                            </a>

                            <?php if ($canAddNews): ?>
                                <a href="addNews.php">
                                    <button class="button primary btn--action" type="button">
                                        News oder Termine hinzufügen
                                    </button>
                                </a>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>
                        <!-- BEARBEITUNGS-MODUS ---------------------------------->
                        <form method="post" class="profile-edit-form">
                            <ul class="profile-activity-list">
                                <li>
                                    <h3>Rolle im Verein</h3>
                                    <input
                                            type="text"
                                            name="rolle_verein"
                                            class="profile-input"
                                            value="<?= isset($user['rolle_verein']) ? htmlspecialchars($user['rolle_verein'], ENT_QUOTES, 'UTF-8') : '' ?>"
                                            placeholder="z.B. Trainer, Spieler, Kassenwart">
                                </li>
                                <li>
                                    <h3>Teams & Trainingszeiten</h3>
                                    <input
                                            type="text"
                                            name="teams_trainingszeiten"
                                            class="profile-input"
                                            value="<?= isset($user['team_info']) ? htmlspecialchars($user['team_info'], ENT_QUOTES, 'UTF-8') : '' ?>"
                                            placeholder="z.B. Herren 1, Di & Do 19–21 Uhr">
                                </li>
                                <li>
                                    <h3>Ehrenamtliche Aufgaben</h3>
                                    <input
                                            type="text"
                                            name="ehrenamtliche_aufgaben"
                                            class="profile-input"
                                            value="<?= isset($user['ehrenamt']) ? htmlspecialchars($user['ehrenamt'], ENT_QUOTES, 'UTF-8') : '' ?>"
                                            placeholder="z.B. Organisation Heimspiele, Sommerfest">
                                </li>
                            </ul>

                            <h2>Statistik</h2>
                            <div class="profile-stats profile-stats--editable">
                                <div class="profile-stat">
                                    <input
                                            type="number"
                                            min="0"
                                            name="spiele"
                                            class="profile-input profile-input--number"
                                            value="<?= isset($user['spiele']) ? (int)$user['spiele'] : '' ?>"
                                            placeholder="0">
                                    <span class="profile-stat__label">Spiele</span>
                                </div>
                                <div class="profile-stat">
                                    <input
                                            type="number"
                                            min="0"
                                            name="tore"
                                            class="profile-input profile-input--number"
                                            value="<?= isset($user['tore']) ? (int)$user['tore'] : '' ?>"
                                            placeholder="0">
                                    <span class="profile-stat__label">Tore</span>
                                </div>
                                <div class="profile-stat">
                                    <input
                                            type="number"
                                            min="0"
                                            name="helfer"
                                            class="profile-input profile-input--number"
                                            value="<?= isset($user['helfer_einsaetze']) ? (int)$user['helfer_einsaetze'] : '' ?>"
                                            placeholder="0">
                                    <span class="profile-stat__label">Einsätze als Helfer</span>
                                </div>
                            </div>

                            <div class="profile-actions">
                                <button class="button accent btn--action" type="button"
                                        onclick="window.location.href='profile.php'">
                                    Abbrechen
                                </button>
                                <button class="button primary btn--action" type="submit" name="save_profile">
                                    Änderungen speichern
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
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
