<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    header('Content-Type: application/json');

    // Überprüfen, ob der Benutzer eingeloggt ist
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Nicht autorisiert.']);
        exit();
    }

    // Eingabedaten aus der Anfrage lesen
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage. Keine Bestell-ID angegeben.']);
        exit();
    }

    $orderId = intval($data['order_id']);
    $userId = $_SESSION['user_id'];
    $isAdmin = ($_SESSION['role'] === 'administrator');

    try {
        // Überprüfen, ob die Bestellung existiert und dem Benutzer gehört (oder Admin ist)
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND (user_id = ? OR ?)");
        $stmt->execute([$orderId, $userId, $isAdmin]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Bestellung nicht gefunden oder keine Berechtigung.']);
            exit();
        }

        // Bestellung aus der Tabelle `order_items` löschen
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);

        // Bestellung aus der Tabelle `orders` löschen
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);

        echo json_encode(['success' => true, 'message' => 'Bestellung erfolgreich storniert.']);
    } catch (Exception $e) {
        error_log("Fehler beim Löschen der Bestellung: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Fehler beim Stornieren der Bestellung.']);
    }
    exit();
?>