<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    header('Content-Type: application/json');

    // Überprüfen, ob der Benutzer eingeloggt ist
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Nicht autorisiert.']);
        exit();
    }

    // Überprüfen, ob der Benutzer ein Administrator ist
    if ($_SESSION['role'] !== 'administrator') {
        echo json_encode(['success' => false, 'message' => 'Keine Berechtigung.']);
        exit();
    }

    // Eingabedaten aus der Anfrage lesen
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['order_id'], $data['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage.']);
        exit();
    }

    $orderId = intval($data['order_id']);
    $productId = intval($data['product_id']);

    try {
        // Überprüfen, ob das Produkt in der Bestellung existiert
        $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ? AND product_id = ?");
        $stmt->execute([$orderId, $productId]);
        $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$orderItem) {
            echo json_encode(['success' => false, 'message' => 'Produkt nicht in der Bestellung gefunden.']);
            exit();
        }

        // Produkt aus der Bestellung entfernen
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ? AND product_id = ?");
        $stmt->execute([$orderId, $productId]);

        echo json_encode(['success' => true, 'message' => 'Produkt erfolgreich entfernt.']);
    } catch (Exception $e) {
        error_log("Fehler beim Entfernen des Produkts: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Fehler beim Entfernen des Produkts.']);
    }
    exit();
?>