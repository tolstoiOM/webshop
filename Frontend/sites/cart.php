<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    require_once '../../Backend/config/config.php';

    $userId = $_SESSION['user_id'];

    // Warenkorb-Daten abrufen
    $stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.name, p.price, p.image_path FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gesamtpreis berechnen
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warenkorb</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js"></script></head>
<body>
    <?php include '../sites/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center">Warenkorb</h2>

        <?php if (empty($cartItems)): ?>
            <div class="alert alert-warning text-center">Ihr Warenkorb ist leer.</div>
        <?php else: ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Menge</th>
                        <th>Preis</th>
                        <th>Gesamt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <?php
                        // Pfad bereinigen und sicherstellen, dass ein führender Slash vorhanden ist
                        $imagePath = '/' . ltrim($item['image_path'], '/');
                        ?>
                        <tr>
                            <td>
                                <img src="/Backend\productpictures<?= $imagePath ?>" alt="<?= $item['name'] ?>" style="width: 50px; height: 50px;">
                                <?= $item['name'] ?>
                            </td>
                            <td>x<?= $item['quantity'] ?></td>
                            <td>€<?= number_format($item['price'], 2) ?></td>
                            <td>€<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-end mt-4">
                <h4>Gesamtpreis: <strong>€<?= number_format($totalPrice, 2) ?></strong></h4>
            </div>

            <!-- Weiter zur Zahlung Button -->
            <div class="text-center mt-4">
                <a href="/Frontend/sites/payment.php" class="btn btn-success">Weiter zur Zahlung</a>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="/index.php" class="btn btn-primary">Weiter einkaufen</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>