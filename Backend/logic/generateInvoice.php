<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Backend/logic/generateInvoice.php

require_once '../config/config.php';

if (!isset($_GET['orderId'])) {
    die('Bestell-ID fehlt.');
}

$orderId = $_GET['orderId'];

// Bestellung abrufen
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    die('Bestellung nicht gefunden.');
}

// Produkte der Bestellung abrufen
$stmt = $pdo->prepare("SELECT oi.quantity, oi.price, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellung ansehen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Bestellung ansehen</h2>
        <div class="card mt-4">
            <div class="card-header">
                <h4>Bestelldetails</h4>
            </div>
            <div class="card-body">
                <p><strong>Bestellnummer:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
                <p><strong>Datum:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
                <p><strong>Gesamtbetrag:</strong> €<?php echo number_format($order['total_price'], 2); ?></p>
                <h5 class="mt-4">Produkte</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Menge</th>
                            <th>Preis</th>
                            <th>Gesamt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>€<?php echo number_format($item['price'], 2); ?></td>
                                <td>€<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>