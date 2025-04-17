<?php
    require_once '../../Backend/config/session.php'; // Start the session
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Redirect to the homepage or login page
    header('Location: ../../index.php');
    exit;
?>