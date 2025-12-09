<?php
//global $pdo;
/** @var PDO $pdo */
require_once __DIR__ . '/inc/db.php'; // DB-Verbindung

$successMsg = '';
$errorMsg   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $entryType   = isset($_POST['entryType']) ? $_POST['entryType'] : '';
    $title       = trim(isset($_POST['title']) ? $_POST['title'] : '');
    $description = trim(isset($_POST['description']) ? $_POST['description'] : '');

    // Grundvalidierung
    if (!in_array($entryType, ['news', 'event'], true)) {
        $errorMsg = 'Bitte wähle, ob News oder Termin.';
    } elseif ($title === '' || $description === '') {
        $errorMsg = 'Bitte fülle alle Pflichtfelder aus.';
    } else {

        try {
            if ($entryType === 'event') {
                // Termin
                $date     = isset($_POST['date']) ? $_POST['date'] : '';
                $time     = isset($_POST['time']) ? $_POST['time'] : '';
                $location = trim(isset($_POST['location']) ? $_POST['location'] : '');

                if ($date === '' || $time === '' || $location === '') {
                    $errorMsg = 'Bitte alle Termin-Felder ausfüllen.';
                } else {
                    $stmt = $pdo->prepare(
                            'INSERT INTO Termin (titel, datum, uhrzeit, veranstaltungsort, beschreibung)
                         VALUES (:titel, :datum, :uhrzeit, :ort, :beschreibung)'
                    );

                    $stmt->execute([
                            ':titel'        => $title,
                            ':datum'        => $date,
                            ':uhrzeit'      => $time,
                            ':ort'          => $location,
                            ':beschreibung' => $description,
                    ]);

                    $successMsg = 'Termin wurde gespeichert.';
                }

            } else {
                // News
                $imagePath = null;

                if (!empty($_FILES['image']['name'])) {
                    // absoluter Server-Pfad: /src/.. = Projektroot → /assets/images/uploads/
                    $uploadDir = __DIR__ . '/../assets/images/uploads/';

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $filename  = uniqid('news_', true) . '.' . $extension;
                    $target    = $uploadDir . $filename;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        // Pfad, der in der DB landet (relativ zu /assets/images)
                        $imagePath = 'uploads/' . $filename;
                    }
                }


                $stmt = $pdo->prepare(
                        'INSERT INTO News (titel, bild, beschreibung)
                     VALUES (:titel, :bild, :beschreibung)'
                );

                $stmt->execute([
                        ':titel'        => $title,
                        ':bild'         => $imagePath,
                        ':beschreibung' => $description,
                ]);

                $successMsg = 'News wurde gespeichert.';
            }

        } catch (PDOException $e) {
            // Fehler behandeln / loggen
            $errorMsg = 'Beim Speichern ist ein Fehler aufgetreten.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>News oder Termin hinzufügen – TSV Dierfeld</title>

    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/addNews.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/breadcrumb.css">
</head>
<body>

<div id="header"></div>

<nav class="breadcrumbs">
    <a href="index.php">Startseite</a>
    <span class="sep">/</span>
    <a href="profile.php">Profil</a>
    <span class="sep">/</span>
    <span class="current">News oder Termin hinzufügen</span>
</nav>

<?php if ($successMsg): ?>
    <div class="flash-message flash-message--success" data-auto-dismiss="true">
        <strong>Erfolg</strong>
        <span><?= htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
<?php endif; ?>

<?php if ($errorMsg): ?>
    <div class="flash-message flash-message--error" data-auto-dismiss="true">
        <strong>Fehler</strong>
        <span><?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
<?php endif; ?>

<main class="page-grid add-entry">
    <div class="page-grid__item add-entry__card">

        <section class="input__section add-entry__section">

            <h2 class="add-entry__title">News oder Termin hinzufügen</h2>
            <p class="add-entry__intro">
                Wähle aus, ob du eine News oder einen Termin eintragen möchtest.
            </p>

            <form class="add-entry__form"
                  id="add-entry-form"
                  method="post"
                  action="addNews.php"
                  enctype="multipart/form-data"
                  novalidate>

                <!-- Art des Eintrags -->
                <div class="input__single add-entry__field-group add-entry__field-group--type">
                    <label class="add-entry__label">
                        Art des Eintrags*
                        <select class="add-entry__control" id="entry-type" name="entryType" required>
                            <option value="">Bitte wählen</option>
                            <option value="news">News</option>
                            <option value="event">Termin</option>
                        </select>
                    </label>
                </div>

                <!-- Titel -->
                <div class="input__single add-entry__field-group add-entry__field-group--title">
                    <label class="add-entry__label">
                        Titel*
                        <input class="add-entry__control" id="entry-title" name="title" type="text"
                               placeholder="Titel eingeben" required>
                    </label>
                    <p class="add-entry__error" data-error-for="title"></p>
                </div>

                <!-- Termin-spezifische Felder (Datum, Uhrzeit, Ort) -->
                <div class="add-entry__field-group add-entry__field-group--event add-entry__field-group--event-hidden">

                    <div class="input__double">
                        <label class="add-entry__label">
                            Datum*
                            <input class="add-entry__control" id="entry-date" name="date" type="date">
                        </label>

                        <label class="add-entry__label">
                            Uhrzeit*
                            <input class="add-entry__control" id="entry-time" name="time" type="time">
                        </label>
                    </div>

                    <div class="input__single">
                        <label class="add-entry__label">
                            Ort*
                            <input class="add-entry__control" id="entry-location" name="location" type="text"
                                   placeholder="Veranstaltungsort">
                        </label>
                    </div>

                    <p class="add-entry__error" data-error-for="event"></p>
                </div>

                <div class="add-entry__field-group add-entry__field-group--image add-entry__field-group--image-hidden">
                    <label class="add-entry__label">
                        Bild zur News (optional)
                        <input class="add-entry__control"
                               id="entry-image"
                               name="image"
                               type="file"
                               accept="image/jpeg, image/png, image/webp">
                    </label>

                    <!-- Vorschau (optional) -->
                    <div class="add-entry__image-preview" id="entry-image-preview"></div>
                </div>

                <!-- Beschreibung -->
                <div class="input__single add-entry__field-group add-entry__field-group--description">
                    <label class="add-entry__label">
                        Beschreibung*
                        <textarea class="add-entry__control add-entry__control--textarea"
                                  id="entry-description"
                                  name="description"
                                  rows="5"
                                  placeholder="Kurzbeschreibung des News-Eintrags oder Termins"
                                  required></textarea>
                    </label>
                    <p class="add-entry__error" data-error-for="description"></p>
                </div>
                <div class="add-entry__intro">
                <p>
                    * Pflichtfeld
                </p>
                </div>
                <!-- Buttons -->
                <div class="input__buttons add-entry__buttons">
                    <button class="button accent btn--action" type="button" onclick="history.back()">
                        Abbrechen
                    </button>
                    <button class="button primary btn--action add-entry__submit" type="submit">
                        Speichern
                    </button>
                </div>

            </form>
        </section>


    </div>
</main>

<div id="footer"></div>

<script src="../assets/js/header.js"></script>
<script src="../assets/js/footer.js"></script>
<script src="../assets/js/addNews.js"></script>

</body>
</html>
