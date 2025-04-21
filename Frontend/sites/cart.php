<?php
    require_once '../../Backend/config/session.php';
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
    <script src="../js/fetchCartAPI.js" defer></script>
    <script src="../js/fetchProductsAPI.js" defer></script>
</head>

<body>
    <?php include '../sites/navbar.php'; ?>
    <div class="container mt-5" id="cart-container">
        <h2 class="text-center">Warenkorb</h2>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Menge</th>
                    <th>Preis</th>
                    <th>Gesamt</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <!-- Dynamische Inhalte werden hier eingefügt -->
            </tbody>
        </table>

        <div class="text-end mt-4">
            <h4>Gesamtpreis: <strong id="total-price">€0.00</strong></h4>
        </div>

        <!-- Weiter zur Zahlung Button -->
        <div class="text-center mt-4">
            <a href="/Frontend/sites/payment.php" class="btn btn-success">Weiter zur Zahlung</a>
        </div>

        <div class="text-center mt-4">
            <a href="/index.php" class="btn btn-primary">Weiter einkaufen</a>
        </div>
    </div>
</body>
</html>