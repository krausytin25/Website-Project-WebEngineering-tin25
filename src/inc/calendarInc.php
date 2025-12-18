<?php
/** @var PDO $pdo */

// alle News (neueste zuerst)
$newsStmt = $pdo->query(
    'SELECT titel, bild, beschreibung
     FROM News
     ORDER BY id DESC'
);
$newsItems = $newsStmt->fetchAll();

// alle Termine (chronologisch)
$terminStmt = $pdo->query(
    'SELECT titel, datum, uhrzeit, veranstaltungsort, beschreibung
     FROM Termin
     ORDER BY datum ASC, uhrzeit ASC'
);
$termine = $terminStmt->fetchAll();

$wochentage = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];

