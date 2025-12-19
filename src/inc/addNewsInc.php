<?php
/** @var PDO $pdo */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

$entryType = $_POST['entryType'] ?? '';
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

/* ---------- Grundvalidierung ---------- */
if (!in_array($entryType, ['news', 'event'], true)) {
    $errorMsg = 'Bitte wähle, ob News oder Termin.';
    return;
}

if ($title === '' || $description === '') {
    $errorMsg = 'Bitte fülle alle Pflichtfelder aus.';
    return;
}

/* Titel-Limit (gegen Missbrauch / DB-Limits) */
if (mb_strlen($title) > 200) {
    $errorMsg = 'Der Titel ist zu lang (max. 200 Zeichen).';
    return;
}

try {

    /* ---------- TERMIN ---------- */
    if ($entryType === 'event') {

        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $location = trim($_POST['location'] ?? '');

        if ($date === '' || $time === '' || $location === '') {
            $errorMsg = 'Bitte alle Termin-Felder ausfüllen.';
            return;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO Termin (titel, datum, uhrzeit, veranstaltungsort, beschreibung)
             VALUES (:titel, :datum, :uhrzeit, :ort, :beschreibung)'
        );

        $stmt->execute([
            ':titel' => $title,
            ':datum' => $date,
            ':uhrzeit' => $time,
            ':ort' => $location,
            ':beschreibung' => $description,
        ]);

        $successMsg = 'Termin wurde gespeichert.';
        return;
    }

    /* ---------- NEWS ---------- */
    $imagePath = null;

    // Upload vorhanden?
    if (isset($_FILES['image']) && is_array($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        // Upload-Fehler?
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'Bild-Upload fehlgeschlagen.';
            return;
        }

        // Größenlimit (z.B. 5 MB)
        $maxBytes = 5 * 1024 * 1024;
        if (!isset($_FILES['image']['size']) || (int)$_FILES['image']['size'] > $maxBytes) {
            $errorMsg = 'Das Bild ist zu groß (max. 5 MB).';
            return;
        }

        $tmpName = $_FILES['image']['tmp_name'];

        // 1) Muss ein echtes Bild sein (prüft Header/Struktur)
        $imgInfo = @getimagesize($tmpName);
        if ($imgInfo === false) {
            $errorMsg = 'Bitte nur gültige Bilddateien hochladen.';
            return;
        }

        // 2) MIME Whitelist
        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        $mime = $imgInfo['mime'] ?? '';
        if (!isset($allowedMime[$mime])) {
            $errorMsg = 'Nur JPG, PNG oder WEBP sind erlaubt.';
            return;
        }

        // 3) Endung aus MIME nehmen (nicht aus Original-Dateiname!)
        $extension = $allowedMime[$mime];

        // 4) Zielordner
        $uploadDir = __DIR__ . '/../../assets/images/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        // 5) Sicherer Dateiname (random)
        $filename = 'news_' . bin2hex(random_bytes(16)) . '.' . $extension;
        $target = $uploadDir . $filename;

        if (!move_uploaded_file($tmpName, $target)) {
            $errorMsg = 'Bild konnte nicht gespeichert werden.';
            return;
        }

        $imagePath = 'uploads/' . $filename;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO News (titel, bild, beschreibung)
         VALUES (:titel, :bild, :beschreibung)'
    );

    $stmt->execute([
        ':titel' => $title,
        ':bild' => $imagePath,
        ':beschreibung' => $description,
    ]);

    $successMsg = 'News wurde gespeichert.';

} catch (PDOException $e) {
    $errorMsg = 'Beim Speichern ist ein Fehler aufgetreten.';
}
