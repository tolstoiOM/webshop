<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    header('Content-Type: application/json');

    // Check if the user is an administrator
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    // Fetch all users with the role "user"
    $stmt = $pdo->prepare("SELECT id, salutation, first_name, last_name, address, zip, city, email, username, status FROM users WHERE role = 'user'");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'users' => $users]);
    exit();
?>