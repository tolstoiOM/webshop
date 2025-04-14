<?php
    // filepath: c:\xampp\htdocs\webshop\Backend\logic\logout_process.php

    session_start(); // Start the session
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Redirect to the homepage or login page
    header('Location: ../../index.php');
    exit;
?>