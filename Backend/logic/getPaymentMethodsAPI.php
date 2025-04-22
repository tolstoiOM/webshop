<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    header('Content-Type: application/json');

    // Überprüfen, ob der Benutzer eingeloggt ist
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Nicht autorisiert.']);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM payment_methods WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'paymentMethods' => $paymentMethods]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Fehler beim Abrufen der Zahlungsmethoden: ' . $e->getMessage()]);
    }
?>