<?php
session_start();

// -------------------------------------------
// 1. Bereits eingeloggt? -> Weiterleiten
// -------------------------------------------
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';


// -------------------------------------------
// 2. Auto-Login über Cookie
// -------------------------------------------
if (isset($_COOKIE['remember_user']) && !empty($_COOKIE['remember_user'])) {

    $emailFromCookie = $_COOKIE['remember_user'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=tsvdierfeld;charset=utf8', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // COOKIE-LOGIN
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


// -------------------------------------------
// 3. Normaler Login per Formular
// -------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password']; // Klartext-Passwort aus Formular

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=tsvdierfeld;charset=utf8', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // LOGIN
        $stmt = $pdo->prepare("SELECT id, email, passwort FROM benutzer WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Passwort prüfen – Klartext-Version
        if ($user && $password === $user['passwort']) {

            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $user['email'];

            // OPTIONAL: Cookie für Auto-Login aktivieren
            // setcookie('remember_user', $user['email'], time() + 86400 * 30, '/');

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
                    <input type="text">
                </label>

                <label>
                    Nachname*
                    <input type="text">
                </label>
            </div>

            <div class="input__single">
                <label>
                    Geburtstag*
                    <input type="date">
                </label>
            </div>

            <div class="input__double">
                <label>
                    Mobil*
                    <input type="text">
                </label>

                <label>
                    Telefon
                    <input type="text">
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
                    <input type="email">
                </label>
            </div>

            <div class="input__single">
                <label>
                    Passwort*
                    <input type="password">
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

        <section class="input__section">
            <h3>Rechnungsadresse</h3>
            <div class="separator"></div>
            <div class="divider"></div>
            <div class="input__double">
                <label>
                    Straße*
                    <input type="text">
                </label>

                <label>
                    Hausnummer*
                    <input type="text">
                </label>
            </div>
            <div class="input__double">
                <label>
                    PLZ*
                    <input type="text">
                </label>

                <label>
                    Ort*
                    <input type="text">
                </label>
            </div>
            <div class="input__single">
                <label>
                    Land*
                    <input type="text">
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
                <button class="button primary btn--action">Anmelden</button>
            </div>

        </section>
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