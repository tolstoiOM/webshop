<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    require_once '../../Backend/config/config.php';

    // Überprüfen, ob der Benutzer ein Administrator ist
    $isAdmin = ($_SESSION['role'] === 'administrator');

    // user_id aus der Anfrage lesen
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $userId = $isAdmin && isset($data['user_id']) ? intval($data['user_id']) : $_SESSION['user_id'];
    
    // Bestellungen des Benutzers abrufen
    $stmt = $pdo->prepare("SELECT id, total_price, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSON-Antwort zurückgeben
    echo json_encode(['orders' => $orders]);
?>