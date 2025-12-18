<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$BASE_URL = "/src/";