<?php
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/db.php';

$user = null;
$isEditMode = false;

$displayName = '';
$initials = '??';
$roleText = '';

$emailDisplay = '';
$mobil = '';
$telefon = '';
$adresse = '';
$eintritt = '';
$mitgliedsnummer = '';
$geburtsdatumFormatiert = '';
$canAddNews = false;

require_once __DIR__ . '/inc/profileInc.php';
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

<?php include __DIR__ . "/../src/components/header.php"; ?>

<nav class="breadcrumbs">
    <a href="index.php">Startseite</a>
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

                <!-- Linke Spalte -->
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

                <!-- Rechte Spalte -->
                <section class="profile-section">
                    <h2>Im Verein aktiv</h2>

                    <?php if (!$isEditMode): ?>

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
                                <span class="profile-stat__number"><?= (int)($user['spiele'] ?? 0) ?></span>
                                <span class="profile-stat__label">Spiele</span>
                            </div>

                            <div class="profile-stat">
                                <span class="profile-stat__number"><?= (int)($user['tore'] ?? 0) ?></span>
                                <span class="profile-stat__label">Tore</span>
                            </div>

                            <div class="profile-stat">
                                <span class="profile-stat__number"><?= (int)($user['helfer_einsaetze'] ?? 0) ?></span>
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

                        <form method="post" class="profile-edit-form">

                            <ul class="profile-activity-list">
                                <li>
                                    <h3>Rolle im Verein</h3>
                                    <input type="text" name="rolle_verein" class="profile-input"
                                           value="<?= htmlspecialchars($user['rolle_verein'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                </li>

                                <li>
                                    <h3>Teams & Trainingszeiten</h3>
                                    <input type="text" name="teams_trainingszeiten" class="profile-input"
                                           value="<?= htmlspecialchars($user['team_info'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                </li>

                                <li>
                                    <h3>Ehrenamtliche Aufgaben</h3>
                                    <input type="text" name="ehrenamtliche_aufgaben" class="profile-input"
                                           value="<?= htmlspecialchars($user['ehrenamt'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                </li>
                            </ul>

                            <h2>Statistik</h2>

                            <div class="profile-stats profile-stats--editable">
                                <div class="profile-stat">
                                    <input type="number" name="spiele" min="0"
                                           class="profile-input profile-input--number"
                                           value="<?= htmlspecialchars($user['spiele'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <span class="profile-stat__label">Spiele</span>
                                </div>

                                <div class="profile-stat">
                                    <input type="number" name="tore" min="0" class="profile-input profile-input--number"
                                           value="<?= htmlspecialchars($user['tore'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <span class="profile-stat__label">Tore</span>
                                </div>

                                <div class="profile-stat">
                                    <input type="number" name="helfer" min="0"
                                           class="profile-input profile-input--number"
                                           value="<?= htmlspecialchars($user['helfer_einsaetze'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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

<?php include __DIR__ . "/../src/components/footer.php"; ?>
<script src="../assets/js/header.js"></script>
</body>
</html>
