<?php
session_start();

if (empty($_SESSION['loggedIn']) || empty($_SESSION['email'])) {
    header('Location: registrationInc.php');
    exit;
}

