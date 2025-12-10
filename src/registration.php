<?php
session_start();

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

/* ---------------------------------------------------
   COOKIE LOGIN
----------------------------------------------------*/
if (isset($_COOKIE['remember_user']) && !empty($_COOKIE['remember_user'])) {

    $emailFromCookie = $_COOKIE['remember_user'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=tsvdierfeld;charset=utf8', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id, email FROM benutzer WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $emailFromCookie]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $user['email'];
            header('Location: 3_dashboard.php');
            exit;
        } else {
            setcookie('remember_user', '', time() - 3600, '/');
        }

    } catch (PDOException $e) {
        error_log("DB-Fehler (Cookie-Login): " . $e->getMessage());
    }
}

/* ---------------------------------------------------
   NORMALER LOGIN
----------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=tsvdierfeld;charset=utf8', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM benutzer WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['passwort'])) {

            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $user['email'];

            header("Location: profile.php");
            exit;

        } else {
            $error = "E-Mail oder Passwort ist falsch.";
        }

    } catch (PDOException $e) {
        error_log("DB-Fehler (Login): " . $e->getMessage());
        $error = "Es ist ein Fehler aufgetreten.";
    }
}

/* ---------------------------------------------------
   REGISTRIERUNG
----------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    // Rollen aus Mehrfach-Auswahl holen
    $allowedRoles = [
            'Volleyball',
            'Handball',
            'Fußball',
            'Turnen',
            'Spieler',
            'Passiv',
            'Abteilungsleiter'
    ];

    $selectedRoles = isset($_POST['rollen']) && is_array($_POST['rollen'])
            ? $_POST['rollen']
            : [];

    // nur erlaubte Werte übernehmen
    $selectedRoles = array_intersect($selectedRoles, $allowedRoles);

    // als kommaseparierte Liste speichern
    $rollenString = implode(', ', $selectedRoles);

    // Formulardaten erfassen
    $data = [
            'anrede' => $_POST['anrede'],
            'vorname' => $_POST['vorname'],
            'nachname' => $_POST['nachname'],
            'geburtstag' => $_POST['geburtstag'],
            'mobil' => $_POST['mobil'],
            'telefon' => $_POST['telefon'],
            'email' => $_POST['email_reg'],
            'passwort' => password_hash($_POST['password_reg'], PASSWORD_DEFAULT),
            'mitgliedstarif' => $_POST['mitgliedsart'],
            'rolle_verein' => $rollenString,
            'strasse' => $_POST['strasse'],
            'hausnummer' => $_POST['hausnummer'],
            'plz' => $_POST['plz'],
            'ort' => $_POST['ort'],
            'land' => $_POST['land'],
            'eintrittsdatum' => date('Y-m-d'),
    ];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=tsvdierfeld;charset=utf8', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prüfen ob E-Mail bereits existiert
        $check = $pdo->prepare("SELECT id FROM benutzer WHERE email = :email");
        $check->execute(['email' => $data['email']]);

        if ($check->rowCount() > 0) {
            $error = "Ein Benutzer mit dieser E-Mail-Adresse existiert bereits.";
        } else {

            // Registrierung speichern
            $stmt = $pdo->prepare("
            INSERT INTO benutzer
            (anrede, vorname, nachname, geburtstag, mobil, telefon, email, passwort,
             mitgliedstarif, rolle_verein, strasse, hausnummer, plz, ort, land, eintrittsdatum)
            VALUES
            (:anrede, :vorname, :nachname, :geburtstag, :mobil, :telefon, :email, :passwort,
             :mitgliedstarif, :rolle_verein, :strasse, :hausnummer, :plz, :ort, :land, :eintrittsdatum)
            ");

            $stmt->execute($data);

            // Mitgliedsnummer nachträglich generieren
            $userId = $pdo->lastInsertId();
            $mitgliedsnummer = 'V-' . str_pad($userId, 4, '0', STR_PAD_LEFT);

            $update = $pdo->prepare("
            UPDATE benutzer 
            SET mitgliedsnummer = :mitgliedsnummer 
            WHERE id = :id
            ");
            $update->execute([
                    'mitgliedsnummer' => $mitgliedsnummer,
                    'id' => $userId,
            ]);

            // Auto-Login
            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $data['email'];

            header("Location: profile.php");
            exit;
        }

    } catch (PDOException $e) {
        error_log("DB-Fehler (Registrierung): " . $e->getMessage());
        $error = "Registrierung fehlgeschlagen.";
    }
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Registrierung – TSV Dierfeld</title>

    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/registration.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/breadcrumb.css">

</head>
<body>

<div id="header"></div>

<nav class="breadcrumbs">
    <a href="index.php">Startseite</a>
    <span class="sep">/</span>
    <span class="current">Anmeldung</span>
</nav>

<main class="page-grid">
    <div class="page-grid__item page-grid__item--row1">
        <section class="input__section">
            <h2>Du bist bereits Mitglied?</h2>
            <p>Logge dich einfach mit deinen bestehenden Nutzerdaten ein.</p>

            <form action="" method="POST">
                <div class="input__double">
                    <label>E-Mail-Adresse
                        <input type="email" name="email" required>
                    </label>

                    <label>Passwort
                        <input type="password" name="password" required>
                    </label>
                </div>

                <div class="input__buttons">
                    <button class="button accent btn--action" onclick="history.back()" type="button">Abbrechen</button>
                    <button class="button primary btn--action" type="submit" name="login">Anmelden</button>
                </div>
            </form>

        </section>
    </div>
    <div class="page-grid__divider">oder</div>

    <div class="page-grid__item page-grid__item--row3">
        <form action="" method="POST">
            <h2>Du bist noch kein Mitglied?</h2>
            <p>Dann kannst du jetzt ein Kundenkonto anlegen!</p>
            <h3>Persönliche Daten</h3>

            <div class="separator"></div>

            <section class="input__section">
                <div class="input__radio">

                    <p>Anrede*

                        <label>
                            <input class="pers-info__radio-input" type="radio" name="anrede" value="herr">
                            Herr
                        </label>

                        <label>
                            <input class="pers-info__radio-input" type="radio" name="anrede" value="frau">
                            Frau
                        </label>

                        <label>
                            <input class="pers-info__radio-input" type="radio" name="anrede" value="divers">
                            Divers
                        </label>
                    </p>
                </div>

                <div class="input__double">
                    <label>
                        Vorname*
                        <input type="text" name="vorname">
                    </label>

                    <label>
                        Nachname*
                        <input type="text" name="nachname">
                    </label>
                </div>

                <div class="input__single">
                    <label>
                        Geburtstag*
                        <input type="date" name="geburtstag">
                    </label>
                </div>

                <div class="input__double">
                    <label>
                        Mobil*
                        <input type="text" name="mobil">
                    </label>

                    <label>
                        Telefon
                        <input type="text" name="telefon">
                    </label>
                </div>

            </section>

            <h3>Anmeldedaten</h3>
            <div class="separator"></div>
            <div class="divider"></div>
            <section class="input__section">
                <div class="input__single">
                    <label>
                        E-Mail-Adresse*
                        <input type="email" name="email_reg">
                    </label>
                </div>

                <div class="input__single">
                    <label>
                        Passwort*
                        <input type="password" name="password_reg">
                    </label>
                </div>

            </section>

            <h3>Mitgliedsart</h3>
            <div class="separator"></div>
            <section class="input__section">
                <div class="membership-type">

                    <label class="membership-type__option">
                        <input type="radio" name="mitgliedsart" value="kinder" class="membership-type__input">
                        <span class="membership-type__content">
                <span class="membership-type__title">Kinder & Jugendliche</span>
                <span class="membership-type__price">30 € / Jahr</span>
            </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="radio" name="mitgliedsart" value="erwachsene" class="membership-type__input">
                        <span class="membership-type__content">
                <span class="membership-type__title">Erwachsene</span>
                <span class="membership-type__price">60 € / Jahr</span>
            </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="radio" name="mitgliedsart" value="ermaessigt" class="membership-type__input">
                        <span class="membership-type__content">
                <span class="membership-type__title">Ermäßigt (Studenten, Azubis, Rentner)</span>
                <span class="membership-type__price">40 € / Jahr</span>
            </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="radio" name="mitgliedsart" value="familie" class="membership-type__input">
                        <span class="membership-type__content">
                <span class="membership-type__title">Familienmitgliedschaft</span>
                <span class="membership-type__price">120 € / Jahr</span>
            </span>
                    </label>

                </div>
            </section>

            <h3>Rollen im Verein</h3>
            <div class="separator"></div>
            <section class="input__section">
                <div class="membership-type">

                    <label class="membership-type__option">
                        <input type="checkbox" name="rollen[]" value="Volleyball" class="membership-type__input">
                        <span class="membership-type__content">
                            <span class="membership-type__title">Volleyball</span>
                            <span class="membership-type__price">z.B. Spieler, Trainer</span>
                        </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="checkbox" name="rollen[]" value="Handball" class="membership-type__input">
                        <span class="membership-type__content">
                            <span class="membership-type__title">Handball</span>
                            <span class="membership-type__price">Aktiv oder passiv</span>
                        </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="checkbox" name="rollen[]" value="Fußball" class="membership-type__input">
                        <span class="membership-type__content">
                            <span class="membership-type__title">Fußball</span>
                            <span class="membership-type__price">Aktiv oder passiv</span>
                        </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="checkbox" name="rollen[]" value="Turnen" class="membership-type__input">
                        <span class="membership-type__content">
                            <span class="membership-type__title">Turnen</span>
                            <span class="membership-type__price">Kinder, Jugend, Erwachsene</span>
                        </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="checkbox" name="rollen[]" value="Spieler" class="membership-type__input">
                        <span class="membership-type__content">
                            <span class="membership-type__title">Spieler</span>
                            <span class="membership-type__price">Im aktiven Spielbetrieb</span>
                        </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="checkbox" name="rollen[]" value="Passiv" class="membership-type__input">
                        <span class="membership-type__content">
                            <span class="membership-type__title">Passiv</span>
                            <span class="membership-type__price">Unterstützt den Verein ohne Spielbetrieb</span>
                        </span>
                    </label>

                    <label class="membership-type__option">
                        <input type="checkbox" name="rollen[]" value="Abteilungsleiter" class="membership-type__input">
                        <span class="membership-type__content">
                            <span class="membership-type__title">Abteilungsleiter</span>
                            <span class="membership-type__price">Erweiterte Rechte (z.B. News & Termine)</span>
                        </span>
                    </label>

                </div>
            </section>

            <section class="input__section">
                <h3>Rechnungsadresse</h3>
                <div class="separator"></div>
                <div class="divider"></div>
                <div class="input__double">
                    <label>
                        Straße*
                        <input type="text" name="strasse">
                    </label>

                    <label>
                        Hausnummer*
                        <input type="text" name="hausnummer">
                    </label>
                </div>
                <div class="input__double">
                    <label>
                        PLZ*
                        <input type="text" name="plz">
                    </label>

                    <label>
                        Ort*
                        <input type="text" name="ort">
                    </label>
                </div>
                <div class="input__single">
                    <label>
                        Land*
                        <input type="text" name="land">
                    </label>
                </div>


                <div class="input__text">
                    <label>
                        <input type="checkbox">
                        Durch das Absenden des Formulars
                        werden die von dir angegebenen
                        personenbezogenen Daten durch uns erhoben.
                        Zur datenschutzrechtlichen Behandlung der von dir gemachten Angaben
                        verweisen wir im Übrigen auf
                        unsere Datenschutzerklärung.*
                    </label>

                </div>
                <div class="input__text">
                    <p>
                        * Pflichtfelder
                    </p>
                </div>


                <div class="input__buttons">
                    <button class="button accent btn--action" onclick="history.back()">Abbrechen</button>
                    <button class="button primary btn--action" type="submit" name="register">Anmelden</button>
                </div>

            </section>
        </form>
    </div>

    <div class="page-grid__divider">oder</div>

    <div class="page-grid__item page-grid__item--row4">
        <h2>Antrag herunterladen</h2>
        <p>Drucke und fülle den Antrag ganz einfach aus, schicke ihne an unsere Adresse
            und werde Mitglied. Ohne Account, ohne Schnickschnak. </p>

        <a href="../assets/docs/mitgliedsantrag_tsv_dierfeld.pdf"
           download="Beitrittserklaerung-TSV-Dierfeld.pdf"
           class="button primary btn--action">
            Beitrittserklärung als PDF herunterladen
        </a>
    </div>
</main>
<div id="footer"></div>
<script src="../assets/js/header.js"></script>
<script src="../assets/js/footer.js"></script>
</body>
</html>