<?php
// config/db.php

$dsn    = 'mysql:host=127.0.0.1;dbname=tsvDierfeld;charset=utf8mb4';
$dbUser = 'root';
$dbPass = '';   // nicht ins Repo commiten ;)

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    // in Produktion besser ein eigenes Error-Logging nutzen
    die('Datenbank-Verbindung fehlgeschlagen.');
}
