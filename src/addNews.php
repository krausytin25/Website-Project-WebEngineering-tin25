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

<main class="page-grid add-entry">
    <div class="page-grid__item add-entry__card">

        <section class="input__section add-entry__section">

            <h2 class="add-entry__title">News oder Termin hinzufügen</h2>
            <p class="add-entry__intro">
                Wähle aus, ob du eine News oder einen Termin eintragen möchtest.
            </p>

            <form class="add-entry__form" id="add-entry-form" novalidate>

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
                            <input class="add-entry__control" id="entry-date" name="date" type="date" required>
                        </label>

                        <label class="add-entry__label">
                            Uhrzeit*
                            <input class="add-entry__control" id="entry-time" name="time" type="time" required>
                        </label>
                    </div>

                    <div class="input__single">
                        <label class="add-entry__label">
                            Ort*
                            <input class="add-entry__control" id="entry-location" name="location" type="text"
                                   placeholder="Veranstaltungsort" required>
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
