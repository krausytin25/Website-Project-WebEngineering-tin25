<?php
$newsItems = [];
$termine = [];
$wochentage = [];

require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/calendarInc.php';
?>


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
<?php include __DIR__ . "/../src/components/header.php"; ?>

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
                    <?php if (empty($newsItems)): ?>
                        <p>Aktuell liegen keine News vor.</p>
                    <?php else: ?>
                        <?php foreach ($newsItems as $news): ?>
                            <article class="news-card">
                                <div class="news-card__image-placeholder">
                                    <?php if (!empty($news['bild'])): ?>
                                        <img src="../assets/images/<?= htmlspecialchars($news['bild'], ENT_QUOTES, 'UTF-8') ?>"
                                             alt="<?= htmlspecialchars($news['titel'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?php else: ?>
                                        <div class="news-card__no-image">
                                            Kein Bild vorhanden
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="news-card__content">
                                    <h3><?= htmlspecialchars($news['titel'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p><?= nl2br(htmlspecialchars($news['beschreibung'], ENT_QUOTES, 'UTF-8')) ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <aside class="calendar-section">
                <h2>Terminkalender</h2>

                <div class="calendar-card__year">
                    <?= date('Y') ?>
                </div>

                <ul class="calendar-list">
                    <?php if (empty($termine)): ?>
                        <li class="calendar-event">
                            <div class="calendar-event__info">
                                <h3>Aktuell sind keine Termine eingetragen.</h3>
                            </div>
                        </li>
                    <?php else: ?>
                        <?php foreach ($termine as $termin): ?>
                            <?php
                            $dt = new DateTime($termin['datum'] . ' ' . $termin['uhrzeit']);
                            $tagMonat = $dt->format('d.m');
                            $wochentag = $wochentage[(int)$dt->format('w')]; // 0..6
                            $uhrzeit = $dt->format('H:i');
                            ?>
                            <li class="calendar-event">
                                <div class="calendar-event__date secondary">
                    <span class="calendar-event__day">
                        <?= htmlspecialchars($tagMonat, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                                    <span class="calendar-event__weekday">
                        <?= htmlspecialchars($wochentag, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                                </div>
                                <div class="calendar-event__info">
                                    <h3><?= htmlspecialchars($termin['titel'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p>
                                        <?= htmlspecialchars($uhrzeit, ENT_QUOTES, 'UTF-8') ?>
                                        ·
                                        <?= htmlspecialchars($termin['veranstaltungsort'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                    <?php if (!empty($termin['beschreibung'])): ?>
                                        <p><?= nl2br(htmlspecialchars($termin['beschreibung'], ENT_QUOTES, 'UTF-8')) ?></p>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
<?php include __DIR__ . "/../src/components/footer.php"; ?>
<script src="../assets/js/header.js"></script>
</body>
</html>