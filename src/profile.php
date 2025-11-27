<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profile</title>
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
                <span class="profile-avatar__initials">MS</span>
            </div>
            <div class="profile-main-info">
                <h1>Max Schneider</h1>
                <p class="profile-role">Mitglied · Spieler Herren 1</p>
                <div class="profile-badges">
                    <span class="badge badge--terracotta">Kassenwart</span>
                    <span class="badge badge--terracotta">Herrenmannschaft</span>
                    <span class="badge badge--terracotta">Seit 2019 dabei</span>
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
                            <dd>max.schneider@example.com</dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Telefon</dt>
                            <dd>+49 170 1234567</dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Adresse</dt>
                            <dd>Musterstraße 12, 12345 Musterstadt</dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Eintrittsdatum</dt>
                            <dd>01.08.2019</dd>
                        </div>
                        <div class="profile-data__row">
                            <dt>Mitgliedsnummer</dt>
                            <dd>V-1024</dd>
                        </div>
                    </dl>


                </section>

                <!-- Rechte Spalte: Vereinsinfos & Aktivitäten -->
                <section class="profile-section">
                    <h2>Im Verein aktiv</h2>
                    <ul class="profile-activity-list">
                        <li>
                            <h3>Rolle im Vorstand</h3>
                            <p>Kassenwart seit 2022. Zuständig für Mitgliedsbeiträge und Budgetplanung.</p>
                        </li>
                        <li>
                            <h3>Teams & Trainingszeiten</h3>
                            <p>Spieler in der Herren 1, Training dienstags & donnerstags 19:00 – 21:00 Uhr.</p>
                        </li>
                        <li>
                            <h3>Ehrenamtliche Aufgaben</h3>
                            <p>Organisation von Heimspieltagen und Sommerfest.</p>
                        </li>
                    </ul>

                    <h2>Statistik</h2>
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <span class="profile-stat__number">47</span>
                            <span class="profile-stat__label">Spiele</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat__number">12</span>
                            <span class="profile-stat__label">Tore</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat__number">5</span>
                            <span class="profile-stat__label">Einsätze als Helfer</span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="">
                        <button class="button primary btn--action">Profil bearbeiten</button>
                        </a>
                        <a href="addNews.php">
                        <button class="button primary btn--action">News oder Termine hinzufügen</button>
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