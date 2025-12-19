<?php
/** @var PDO $pdo */

// Session läuft über auth.php
$email = $_SESSION['email'] ?? '';

$isEditMode = isset($_GET['edit']) && $_GET['edit'] === '1';

/* ---------- Speichern ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {

    $rolleVerein = $_POST['rolle_verein'] ?? '';
    $teamsZeiten = $_POST['teams_trainingszeiten'] ?? '';
    $ehrenamt = $_POST['ehrenamtliche_aufgaben'] ?? '';

    $spiele = ($_POST['spiele'] ?? '') !== '' ? (int)$_POST['spiele'] : null;
    $tore = ($_POST['tore'] ?? '') !== '' ? (int)$_POST['tore'] : null;
    $helfer = ($_POST['helfer'] ?? '') !== '' ? (int)$_POST['helfer'] : null;

    $update = $pdo->prepare("
        UPDATE benutzer
        SET
            rolle_verein     = :rolle,
            team_info        = :teams,
            ehrenamt         = :ehrenamt,
            spiele           = :spiele,
            tore             = :tore,
            helfer_einsaetze = :helfer
        WHERE email = :email
        LIMIT 1
    ");

    $update->execute([
        ':rolle' => $rolleVerein,
        ':teams' => $teamsZeiten,
        ':ehrenamt' => $ehrenamt,
        ':spiele' => $spiele,
        ':tore' => $tore,
        ':helfer' => $helfer,
        ':email' => $email,
    ]);

    header('Location: profile.php');
    exit;
}

/* ---------- Benutzer laden ---------- */
$stmt = $pdo->prepare('SELECT * FROM benutzer WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* ---------- Defaults ---------- */
$displayName = 'Unbekannter Benutzer';
$initials = '??';
$roleText = 'Mitglied';
$emailDisplay = htmlspecialchars((string)$email, ENT_QUOTES, 'UTF-8');
$mobil = '';
$telefon = '';
$adresse = '–';
$eintritt = '–';
$mitgliedsnummer = '–';
$geburtsdatumFormatiert = '–';
$canAddNews = false;

/* ---------- Aufbereitung ---------- */
if ($user) {

    $h = function ($v) {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    };

    $displayName = trim(($user['vorname'] ?? '') . ' ' . ($user['nachname'] ?? ''));
    if ($displayName === '') $displayName = 'Mitglied';

    $initials = '';
    if (!empty($user['vorname'])) $initials .= mb_strtoupper(mb_substr($user['vorname'], 0, 1));
    if (!empty($user['nachname'])) $initials .= mb_strtoupper(mb_substr($user['nachname'], 0, 1));
    if ($initials === '') $initials = '??';

    $emailDisplay = $h($user['email'] ?? $email);
    $mobil = $user['mobil'] ?? '';
    $telefon = $user['telefon'] ?? '';

    $adresseParts = [];
    if (!empty($user['strasse']) || !empty($user['hausnummer'])) {
        $adresseParts[] = trim(($user['strasse'] ?? '') . ' ' . ($user['hausnummer'] ?? ''));
    }
    if (!empty($user['plz']) || !empty($user['ort'])) {
        $adresseParts[] = trim(($user['plz'] ?? '') . ' ' . ($user['ort'] ?? ''));
    }
    if (!empty($user['land'])) {
        $adresseParts[] = $user['land'];
    }
    $adresse = $adresseParts ? implode(', ', $adresseParts) : '–';

    if (!empty($user['eintrittsdatum'])) {
        try {
            $eintritt = (new DateTime($user['eintrittsdatum']))->format('d.m.Y');
        } catch (Exception $e) {
            $eintritt = (string)$user['eintrittsdatum'];
        }
    }

    if (!empty($user['geburtstag'])) {
        try {
            $geburtsdatumFormatiert = (new DateTime($user['geburtstag']))->format('d.m.Y');
        } catch (Exception $e) {
            $geburtsdatumFormatiert = (string)$user['geburtstag'];
        }
    }

    $mitgliedsnummer = $user['mitgliedsnummer'] ?? '–';

    $canAddNews = !empty($user['can_add_news']) && (int)$user['can_add_news'] === 1;

    switch ($user['mitgliedstarif'] ?? '') {
        case 'kinder':
            $roleText = 'Mitglied · Kinder & Jugendliche';
            break;
        case 'erwachsene':
            $roleText = 'Mitglied · Erwachsene';
            break;
        case 'ermaessigt':
            $roleText = 'Mitglied · Ermäßigt';
            break;
        case 'familie':
            $roleText = 'Mitglied · Familienmitgliedschaft';
            break;
        default:
            $roleText = 'Mitglied';
    }
}
