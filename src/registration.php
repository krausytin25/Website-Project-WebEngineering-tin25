<?php
$successMsg = '';
$errorMsg = '';

require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/registrationInc.php';
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

<?php include __DIR__ . "/../src/components/header.php"; ?>

<nav class="breadcrumbs">
    <a href="index.php">Startseite</a>
    <span class="sep">/</span>
    <span class="current">Anmeldung</span>
</nav>

<?php if ($successMsg !== ''): ?>
    <div class="flash-message flash-message--success" data-auto-dismiss="true">
        <strong>Erfolg</strong>
        <span><?= htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
<?php endif; ?>

<?php if ($errorMsg !== ''): ?>
    <div class="flash-message flash-message--error" data-auto-dismiss="true">
        <strong>Fehler</strong>
        <span><?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
<?php endif; ?>

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
                            <input class="pers-info__radio-input" type="radio" name="anrede" value="herr" required>
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
                        <input type="text" name="vorname" required>
                    </label>

                    <label>
                        Nachname*
                        <input type="text" name="nachname" required>
                    </label>
                </div>

                <div class="input__single">
                    <label>
                        Geburtstag* (mind. 18 Jahre)
                        <input type="date" name="geburtstag" required>
                    </label>
                </div>

                <div class="input__double">
                    <label>
                        Mobil*
                        <input type="text" name="mobil" inputmode="tel" pattern="\+?[0-9]+" required>
                    </label>

                    <label>
                        Telefon
                        <input type="text" name="telefon" inputmode="tel" pattern="\+?[0-9]*">
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
                        <input type="email" name="email_reg" required>
                    </label>
                </div>

                <div class="input__single">
                    <label>
                        Passwort*
                        <input type="password" name="password_reg" required>
                    </label>
                </div>
            </section>

            <h3>Mitgliedsart</h3>
            <div class="separator"></div>

            <section class="input__section">
                <div class="membership-type">

                    <label class="membership-type__option">
                        <input type="radio" name="mitgliedsart" value="kinder" class="membership-type__input" required>
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
                    <!-- Rollen sind nur Info/Text, Rechte werden NICHT daraus abgeleitet -->
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
                            <span class="membership-type__price">Hinweis: Rechte werden initial vergeben</span>
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
                        <input type="text" name="strasse" required>
                    </label>

                    <label>
                        Hausnummer*
                        <input type="text" name="hausnummer" required>
                    </label>
                </div>

                <div class="input__double">
                    <label>
                        PLZ*
                        <input type="text" name="plz" inputmode="numeric" pattern="[0-9]+" required>
                    </label>

                    <label>
                        Ort*
                        <input type="text" name="ort" required>
                    </label>
                </div>

                <div class="input__single">
                    <label>
                        Land*
                        <input type="text" name="land" required>
                    </label>
                </div>

                <div class="input__text">
                    <label>
                        <input type="checkbox" required>
                        Durch das Absenden des Formulars werden die von dir angegebenen personenbezogenen Daten durch
                        uns erhoben. Zur datenschutzrechtlichen Behandlung verweisen wir auf unsere
                        Datenschutzerklärung.*
                    </label>
                </div>

                <div class="input__text">
                    <p>* Pflichtfelder</p>
                </div>

                <div class="input__buttons">
                    <button class="button accent btn--action" onclick="history.back()" type="button">Abbrechen</button>
                    <button class="button primary btn--action" type="submit" name="register">Anmelden</button>
                </div>

            </section>
        </form>
    </div>

    <div class="page-grid__divider">oder</div>

    <div class="page-grid__item page-grid__item--row4">
        <h2>Antrag herunterladen</h2>
        <p>Drucke und fülle den Antrag ganz einfach aus, schicke ihn an unsere Adresse und werde Mitglied.</p>

        <a href="../assets/docs/mitgliedsantrag_tsv_dierfeld.pdf"
           download="Beitrittserklaerung-TSV-Dierfeld.pdf"
           class="button primary btn--action">
            Beitrittserklärung als PDF herunterladen
        </a>
    </div>
</main>

<?php include __DIR__ . "/../src/components/footer.php"; ?>
<script src="../assets/js/registration.js"></script>
<script src="../assets/js/header.js"></script>
</body>
</html>
