<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');
    require_once '../config/config.php';

    if (!isset($_GET['orderId'])) {
        echo json_encode(['success' => false, 'message' => 'Bestell-ID fehlt.']);
        exit();
    }

    $orderId = $_GET['orderId'];
    $isAdmin = ($_SESSION['role'] === 'administrator');
    $paymentMethods = [
        '1' => 'Klarna',
        '2' => 'Paypal',
        '3' => 'Apple Pay',
        '4' => 'Kauf auf Rechnung',
        '5' => 'Kreditkarte'
    ];

    // Bestellung abrufen
    $stmt = $pdo->prepare("
        SELECT o.*, u.first_name, u.last_name, u.address, u.zip, u.city, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Bestellung nicht gefunden.']);
        exit();
    }

    // Zahlungsmethode in lesbaren Text umwandeln
    $order['payment_method'] = $paymentMethods[$order['payment_method']] ?? $order['payment_method'];

    // Produkte der Bestellung abrufen
    $stmt = $pdo->prepare("
        SELECT oi.quantity, oi.price, p.name, oi.product_id 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ursprünglichen Gesamtbetrag berechnen (Summe der Einzelpreise)
    $originalPrice = 0;
    foreach ($orderItems as $item) {
        $originalPrice += $item['quantity'] * $item['price'];
    }
    $discount = $originalPrice - $order['total_price'];

    // JSON-Antwort zurückgeben
    echo json_encode([
        'success' => true,
        'order' => $order,
        'orderItems' => $orderItems,
        'originalPrice' => $originalPrice,
        'discount' => $discount,
        'isAdmin' => $isAdmin
    ]);
    exit();
?>