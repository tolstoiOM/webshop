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
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['method'])) {
        echo json_encode(['success' => false, 'message' => 'Zahlungsmethode ist erforderlich.']);
        exit();
    }

    $method = $data['method'];
    $card_number = $data['card_number'] ?? null;
    $expiry_date = $data['expiry_date'] ?? null;
    $cvv = $data['cvv'] ?? null;
    $paypal_email = $data['paypal_email'] ?? null;
    $klarna_account = $data['klarna_account'] ?? null;
    $billing_address = $data['billing_address'] ?? null;
    $apple_pay_token = $data['apple_pay_token'] ?? null;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO payment_methods (user_id, method, card_number, expiry_date, cvv, paypal_email, klarna_account, billing_address, apple_pay_token)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $method, $card_number, $expiry_date, $cvv, $paypal_email, $klarna_account, $billing_address, $apple_pay_token]);

        echo json_encode(['success' => true, 'message' => 'Zahlungsmethode erfolgreich gespeichert.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Fehler beim Speichern der Zahlungsmethode: ' . $e->getMessage()]);
    }
?>