<?php
/** @var PDO $pdo */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location: index.php');
    exit;
}

$normalizePhone = function (string $value): string {
    $value = trim($value);
    if ($value === '') return '';
    $digits = preg_replace('/\D+/', '', $value) ?? '';
    if ($digits === '') return '';
    return '+' . $digits;
};

/* COOKIE LOGIN */
if (isset($_COOKIE['remember_user']) && !empty($_COOKIE['remember_user'])) {

    $emailFromCookie = $_COOKIE['remember_user'];

    try {
        $stmt = $pdo->prepare("SELECT id, email FROM benutzer WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $emailFromCookie]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $user['email'];
            header('Location: 3_dashboard.php');
            exit;
        }

        setcookie('remember_user', '', time() - 3600, '/');

    } catch (PDOException $e) {
        error_log("DB-Fehler (Cookie-Login): " . $e->getMessage());
        $errorMsg = 'Automatischer Login über Cookie ist fehlgeschlagen.';
    }
}

/* NORMALER LOGIN */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM benutzer WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['passwort'])) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $user['email'];
            header("Location: profile.php");
            exit;
        }

        $errorMsg = "E-Mail-Adresse oder Passwort ist falsch, oder der Account existiert nicht.";

    } catch (PDOException $e) {
        error_log("DB-Fehler (Login): " . $e->getMessage());
        $errorMsg = "Beim Login ist ein Fehler aufgetreten.";
    }
}

/* REGISTRIERUNG */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    $allowedRoles = [
        'Volleyball', 'Handball', 'Fußball', 'Turnen', 'Spieler', 'Passiv', 'Abteilungsleiter'
    ];

    $selectedRoles = (isset($_POST['rollen']) && is_array($_POST['rollen'])) ? $_POST['rollen'] : [];
    $selectedRoles = array_intersect($selectedRoles, $allowedRoles);
    $rollenString = implode(', ', $selectedRoles);
    $canAddNews = in_array('Abteilungsleiter', $selectedRoles, true) ? 1 : 0;

    $data = [
        'anrede' => $_POST['anrede'] ?? '',
        'vorname' => $_POST['vorname'] ?? '',
        'nachname' => $_POST['nachname'] ?? '',
        'geburtstag' => $_POST['geburtstag'] ?? '',
        'mobil' => $_POST['mobil'] ?? '',
        'telefon' => $_POST['telefon'] ?? '',
        'email' => $_POST['email_reg'] ?? '',
        'passwort' => password_hash($_POST['password_reg'] ?? '', PASSWORD_DEFAULT),
        'mitgliedstarif' => $_POST['mitgliedsart'] ?? '',
        'rolle_verein' => $rollenString,  // nur Info/Text
        'can_add_news' => $canAddNews,
        'strasse' => $_POST['strasse'] ?? '',
        'hausnummer' => $_POST['hausnummer'] ?? '',
        'plz' => $_POST['plz'] ?? '',
        'ort' => $_POST['ort'] ?? '',
        'land' => $_POST['land'] ?? '',
        'eintrittsdatum' => date('Y-m-d'),
    ];

    // 18+
    try {
        $geb = new DateTime($data['geburtstag']);
        $today = new DateTime('today');
        if ($geb->diff($today)->y < 18) {
            $errorMsg = "Du musst mindestens 18 Jahre alt sein, um dich zu registrieren.";
            return;
        }
    } catch (Exception $e) {
        $errorMsg = "Bitte ein gültiges Geburtsdatum angeben.";
        return;
    }

    // PLZ nur Zahlen
    $plzRaw = trim((string)$data['plz']);
    if ($plzRaw === '' || !ctype_digit($plzRaw)) {
        $errorMsg = "Bitte eine gültige PLZ eingeben (nur Zahlen).";
        return;
    }
    $data['plz'] = $plzRaw;

    // Mobil Pflicht, Telefon optional
    $data['mobil'] = $normalizePhone((string)$data['mobil']);
    if ($data['mobil'] === '') {
        $errorMsg = "Bitte eine gültige Mobilnummer eingeben (nur Zahlen).";
        return;
    }
    $data['telefon'] = $normalizePhone((string)$data['telefon']);

    try {
        $check = $pdo->prepare("SELECT id FROM benutzer WHERE email = :email");
        $check->execute(['email' => $data['email']]);

        if ($check->rowCount() > 0) {
            $errorMsg = "Ein Benutzer mit dieser E-Mail-Adresse existiert bereits.";
            return;
        }

        $stmt = $pdo->prepare("
            INSERT INTO benutzer
            (anrede, vorname, nachname, geburtstag, mobil, telefon, email, passwort,
             mitgliedstarif, rolle_verein, can_add_news,
             strasse, hausnummer, plz, ort, land, eintrittsdatum)
            VALUES
            (:anrede, :vorname, :nachname, :geburtstag, :mobil, :telefon, :email, :passwort,
             :mitgliedstarif, :rolle_verein, :can_add_news,
             :strasse, :hausnummer, :plz, :ort, :land, :eintrittsdatum)
        ");
        $stmt->execute($data);

        $userId = $pdo->lastInsertId();
        $mitgliedsnummer = 'V-' . str_pad((string)$userId, 4, '0', STR_PAD_LEFT);

        $update = $pdo->prepare("
            UPDATE benutzer
            SET mitgliedsnummer = :mitgliedsnummer
            WHERE id = :id
        ");
        $update->execute([
            'mitgliedsnummer' => $mitgliedsnummer,
            'id' => $userId,
        ]);

        $_SESSION['loggedIn'] = true;
        $_SESSION['email'] = $data['email'];

        header("Location: profile.php");
        exit;

    } catch (PDOException $e) {
        error_log("DB-Fehler (Registrierung): " . $e->getMessage());
        $errorMsg = "Registrierung fehlgeschlagen.";
    }
}
