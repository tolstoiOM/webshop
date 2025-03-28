<?php
// filepath: c:\xampp\htdocs\webshop\Backend\logic\register_process.php

require_once '../config/config.php';
require_once 'AuthLogic.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authLogic = new AuthLogic($pdo);

    // Call the registerUser method
    $result = $authLogic->registerUser($_POST);

    if ($result['success']) {
        // Redirect to login page or show success message
        header('Location: ../../Frontend/sites/login.php?success=1');
        exit;
    } else {
        // Redirect back to registration page with error message
        header('Location: ../../Frontend/sites/registration.php?error=' . urlencode($result['message']));
        exit;
    }
}