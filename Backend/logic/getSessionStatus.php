<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'loggedIn' => true,
            'role' => $_SESSION['role'] ?? null
        ]);
    } else {
        echo json_encode(['loggedIn' => false]);
    }
    exit;
?>