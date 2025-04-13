<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Backend/api/getOrderDetails.php

header('Content-Type: application/json');

require_once '../config/config.php';

if (!isset($_GET['orderId'])) {
    echo json_encode(['success' => false, 'message' => 'Bestell-ID fehlt.']);
    exit();
}

$orderId = $_GET['orderId'];

// Bestellung abrufen
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Bestellung nicht gefunden.']);
    exit();
}

// Produkte der Bestellung abrufen
$stmt = $pdo->prepare("SELECT oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSON-Antwort zurückgeben
echo json_encode([
    'success' => true,
    'order' => $order,
    'orderItems' => $orderItems
]);
exit();