<?php
require_once '../../Backend/config/session.php';

// Überprüfen, ob der Benutzer eingeloggt und ein Admin ist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header('Location: /index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gutscheine verwalten</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/adminCouponAPI.js" defer></script>
</head>
<body>
    <?php include '../sites/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">Gutscheine verwalten</h1>

        <!-- Gutscheinübersicht -->
        <div id="coupons-container" class="mt-4">
            <!-- Gutscheine werden hier dynamisch geladen -->
        </div>

        <!-- Formular zum Erstellen eines neuen Gutscheins -->
        <div class="mt-5">
            <h2>Neuen Gutschein erstellen</h2>
            <form id="coupon-form">
                <div class="mb-3">
                    <label for="coupon-value" class="form-label">Geldwert (€)</label>
                    <input type="number" class="form-control" id="coupon-value" name="value" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="coupon-expiry" class="form-label">Ablaufdatum</label>
                    <input type="date" class="form-control" id="coupon-expiry" name="expires_at" required>
                </div>
                <button type="submit" class="btn btn-primary">Gutschein erstellen</button>
            </form>
        </div>
    </div>
</body>
</html>