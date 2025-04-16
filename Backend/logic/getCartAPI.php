<?php
require_once '../config/session.php';
header('Content-Type: application/json');

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Benutzer nicht eingeloggt.']);
    exit;
}

require_once '../config/config.php';

$userId = $_SESSION['user_id'];

// Warenkorb-Daten abrufen
$stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.name, p.price, p.image_path 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = ?");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gesamtpreis berechnen
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// JSON-Antwort zurückgeben
echo json_encode([
    'cartItems' => $cartItems,
    'totalPrice' => number_format($totalPrice, 2)
]);
?>