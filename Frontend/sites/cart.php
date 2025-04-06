<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Frontend/sites/cart.php -->
<?php
session_start();
require_once '../../Backend/config/config.php';

$userId = $_SESSION['user_id']; // Benutzer-ID aus der Session

// Produkte aus dem Warenkorb abrufen
$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.description, p.price, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->execute($cart);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <script src="../js/script.js"></script>
</head>
<body>

    <?php include '../sites/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center">Ihr Warenkorb</h2>
        <div class="mt-4">
            <?php if (!empty($products)): ?>
                <ul class="list-group">
                    <?php foreach ($products as $product): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <p><strong>Preis:</strong> €<?php echo htmlspecialchars($product['price']); ?></p>
                            </div>
                            <button class="btn btn-danger remove-from-cart" data-id="<?php echo $product['id']; ?>">Entfernen</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-4 text-end">
                    <button class="btn btn-primary" id="proceed-to-payment">Weiter zur Zahlung</button>
                </div>
            <?php else: ?>
                <p class="text-center">Ihr Warenkorb ist leer.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Produkt aus dem Warenkorb entfernen
            $('.remove-from-cart').click(function () {
                const productId = $(this).data('id');
                $.ajax({
                    url: '../../Backend/logic/ProductLogic.php',
                    method: 'POST',
                    data: { action: 'removeFromCart', productId: productId },
                    success: function (response) {
                        location.reload(); // Seite neu laden, um die Änderungen anzuzeigen
                    },
                    error: function () {
                        alert('Fehler beim Entfernen des Produkts.');
                    }
                });
            });

            // Weiter zur Zahlung
            $('#proceed-to-payment').click(function () {
                window.location.href = 'payment.php'; // Weiterleitung zur Zahlungsseite
            });
        });
    </script>
</body>
</html>