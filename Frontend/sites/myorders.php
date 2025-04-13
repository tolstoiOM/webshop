<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Frontend/sites/myorders.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../../Backend/config/config.php';

$userId = $_SESSION['user_id'];

// Bestellungen des Benutzers abrufen
$stmt = $pdo->prepare("SELECT id, total_price, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Bestellungen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Meine Bestellungen</h2>
        <div class="mt-4">
            <?php if (!empty($orders)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bestellnummer</th>
                            <th>Datum</th>
                            <th>Status</th>
                            <th>Gesamtbetrag</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td>€<?php echo number_format($order['total_price'], 2); ?></td>
                                <td>
                                    <a href="/webshop/Backend/logic/generateInvoice.php?orderId=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">Ansehen</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Keine Bestellungen gefunden.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>