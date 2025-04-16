<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    require_once '../../Backend/config/config.php';

    $userId = $_SESSION['user_id'];

    // Bestellungen des Benutzers abrufen
    $stmt = $pdo->prepare("SELECT id, total_price, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSON-Antwort zurückgeben
    echo json_encode(['orders' => $orders]);
?>