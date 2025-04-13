<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Frontend/sites/myorders.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../../Backend/config/config.php';

$userId = $_SESSION['user_id'];

// Bestellung löschen, falls `deleteOrderId` übergeben wurde
if (isset($_GET['deleteOrderId'])) {
    $deleteOrderId = $_GET['deleteOrderId'];

    // Überprüfen, ob die Bestellung dem Benutzer gehört
    $stmt = $pdo->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$deleteOrderId, $userId]);
    $order = $stmt->fetch();

    if ($order) {
        // Bestellung und zugehörige Produkte löschen
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->execute([$deleteOrderId]);

        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$deleteOrderId]);

        $message = "Die Bestellung wurde erfolgreich storniert.";
    } else {
        $message = "Die Bestellung konnte nicht gefunden werden oder gehört nicht Ihnen.";
    }
}

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

        <!-- Erfolgsmeldung anzeigen -->
        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

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
                            <th>Aktionen</th>
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
                                    <a href="/webshop/Backend/logic/generateInvoice.php?orderId=<?php echo $order['id']; ?>" class="btn btn-sm btn-secondary">Ansehen</a>
                                </td>
                                <td>
                                    <a href="?deleteOrderId=<?php echo $order['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Möchten Sie diese Bestellung wirklich stornieren?');">Stornieren</a>
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