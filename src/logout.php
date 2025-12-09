<?php
session_start();
session_unset();
session_destroy();

// Cookie löschen
setcookie('remember_user', '', time() - 3600, '/');

header("Location: index.php");
exit;