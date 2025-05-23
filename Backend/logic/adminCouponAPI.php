<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';

    header('Content-Type: application/json');

    // Nur Admins dürfen diese API verwenden
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
        echo json_encode(['success' => false, 'message' => 'Zugriff verweigert.']);
        exit();
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // Alle Gutscheine abrufen
        $stmt = $pdo->query("SELECT id, code, value, residual_value, cashed, created_at, expires_at FROM coupons");
        $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Status (gültig/abgelaufen/eingelöst) hinzufügen
        foreach ($coupons as &$coupon) {
            if ($coupon['cashed'] == 1 && $coupon['residual_value'] == 0) {
                $coupon['status'] = 'Eingelöst';
            } elseif (new DateTime($coupon['expires_at']) < new DateTime()) {
                $coupon['status'] = 'Abgelaufen';
            } else {
                $coupon['status'] = 'Gültig';
            }
        }
        echo json_encode(['success' => true, 'coupons' => $coupons]);

    } elseif ($method === 'POST') {
        // Neuen Gutschein erstellen
        $value = $_POST['value'];
        $expires_at = $_POST['expires_at'];

        // Zufälligen 5-stelligen Code generieren
        $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

        $stmt = $pdo->prepare("INSERT INTO coupons (code, value, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$code, $value, $expires_at]);

        echo json_encode(['success' => true, 'message' => 'Gutschein erfolgreich erstellt.']);
}