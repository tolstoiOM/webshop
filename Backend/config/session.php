<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_set_cookie_params(0); // Session wird beim Schließen des Browsers gelöscht (Session-Lebensdauer = 0)

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>