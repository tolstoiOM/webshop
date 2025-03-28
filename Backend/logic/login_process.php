<?php
// filepath: c:\xampp\htdocs\webshop\Backend\logic\login_process.php

require_once '../config/config.php';
require_once 'AuthLogic.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authLogic = new AuthLogic($pdo);

    // Get the email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call the loginUser method
    $result = $authLogic->loginUser($email, $password);

    if ($result['success']) {
        // Redirect to the homepage or dashboard
        header('Location: ../../Frontend/sites/index.php?login=success');
        exit;
    } else {
        // Redirect back to the login page with an error message
        header('Location: ../../Frontend/sites/login.php?error=' . urlencode($result['message']));
        exit;
    }
}