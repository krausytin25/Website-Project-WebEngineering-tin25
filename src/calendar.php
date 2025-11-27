<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Calender</title>

    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/calendar.css">
    <link rel="stylesheet" href="../assets/css/breadcrumb.css">

</head>
<body>
<div id="header"></div>

<nav class="breadcrumbs">
    <a href="index.php">Startseite</a>
    <span class="sep">/</span>
    <span class="current">Kalender</span>
</nav>

<main class="page-news">
    <div class="page-news__inner">

        <h1>Aktuelles und Terminkalender</h1>

        <div class="news-layout">

            <section class="news-section">
                <h2>Aktuelles</h2>
                <div class="news-section__scroll">
                <article class="news-card">
                    <div class="news-card__image-placeholder">
                        <img src="../assets/images/Trikot.png" alt="Neuer Trikotsatz"/>
                    </div>
                    <div class="news-card__content">
                        <h3>Neuer Trikotsatz für die A-Jugend</h3>
                        <p>Dank unserer Sponsoren hat die A-Jugend einen neuen Trikotsatz erhalten.</p>
                    </div>
                </article>

                <article class="news-card">
                    <div class="news-card__image-placeholder">
                        <img src="../assets/images/Spieltag.png" alt="Heimspiel"/>
                    </div>
                    <div class="news-card__content">
                        <h3>Heimspiel am Samstag</h3>
                        <p>Kommt vorbei und unterstützt die 1. Mannschaft!</p>
                    </div>
                </article>

                <article class="news-card">
                    <div class="news-card__image-placeholder">
                        <img src="../assets/images/Yoga.png" alt="Yoga Kurs"/>
                    </div>
                    <div class="news-card__content">
                        <h3>Yoga-Kurs startet wieder</h3>
                        <p>Ab nächster Woche geht es mit dem beliebten Kurs weiter.</p>
                    </div>
                </article>
                    </div>
            </section>

            <aside class="calendar-section">
                <h2>Terminkalender</h2>

                <div class="calendar-card">
                    <div class="calendar-card__year">
                        2025
                    </div>

                    <ul class="calendar-list">
                        <li class="calendar-event">
                            <div class="calendar-event__date secondary">
                                <span class="calendar-event__day">09.11</span>
                                <span class="calendar-event__weekday">Sonntag</span>
                            </div>
                            <div class="calendar-event__info">
                                <h3>Heimspiel TSV – SV Beispielo</h3>
                                <p>15:00 Uhr · Sportplatz Dierfeld</p>
                            </div>
                        </li>

                        <li class="calendar-event">
                            <div class="calendar-event__date secondary">
                                <span class="calendar-event__day">15.11</span>
                                <span class="calendar-event__weekday">Samstag</span>
                            </div>
                            <div class="calendar-event__info">
                                <h3>Vereinsabend &amp; Ehrungen</h3>
                                <p>19:30 Uhr · Vereinsheim</p>
                            </div>
                        </li>

                        <li class="calendar-event">
                            <div class="calendar-event__date secondary">
                                <span class="calendar-event__day">25.11</span>
                                <span class="calendar-event__weekday">Dienstag</span>
                            </div>
                            <div class="calendar-event__info">
                                <h3>Yoga-Kurs Einsteiger</h3>
                                <p>18:00 Uhr – 19:00 Uhr · Halle</p>
                            </div>
                        </li>

                        <li class="calendar-event">
                            <div class="calendar-event__date secondary">
                                <span class="calendar-event__day">13.12</span>
                                <span class="calendar-event__weekday">Samstag</span>
                            </div>
                            <div class="calendar-event__info">
                                <h3>Winterfest</h3>
                                <p>ab 11:00 Uhr · Festplatz</p>
                            </div>
                        </li>
                    </ul>

                    <div class="calendar-note">
                        <div class="calendar-note__color accent"></div>
                        <div class="calendar-note__text">
                            <strong>Hinweis</strong>
                            <p>Termine können sich ändern</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>
<div id="footer"></div>
<script src="../assets/js/header.js"></script>
<script src="../assets/js/footer.js"></script>
</body>
</html>