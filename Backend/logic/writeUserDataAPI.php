<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['userId'], $data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage.']);
        exit();
    }

    $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :userId");
    $stmt->execute(['status' => $data['status'], 'userId' => $data['userId']]);

    echo json_encode(['success' => true, 'message' => 'Status erfolgreich aktualisiert.']);
    exit();
?>