<?php
    require_once '../../Backend/config/session.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahlung durchführen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/writePaymentAPI.js" defer></script>
    <script src="../js/fetchProductsAPI.js" defer></script>
    <script src="../js/fetchPaymentMethods.js" defer></script>
</head>

<body>
    <?php include '../sites/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Zahlung durchführen</h2>
        <div class="card mt-4">
            <div class="card-header">
                <h4>Zahlungsdetails</h4>
            </div>
            <div class="card-body">
                <form id="paymentForm">
                    <div class="mb-3">
                        <label for="method" class="form-label">Zahlungsmethode</label>
                        <select class="form-select" id="method" name="method" required>
                            <!-- Zahlungsmethoden werden hier dynamisch geladen -->
                        </select>
                    </div>
                    <div id="paymentDetails" class="mb-3">
                        <!-- Details der ausgewählten Zahlungsmethode werden hier angezeigt -->
                    </div>
                    <div id="payment-error" class="text-danger mb-3" style="display: none;"></div>
                    <button type="button" id="submitPayment" class="btn btn-primary">Zahlung durchführen</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4>Gutscheincode</h4>
            </div>
            <div class="card-body">
                <label for="couponCode" class="form-label"></label>
                <input type="text" class="form-control" id="couponCode" name="coupon_code" placeholder="Gutscheincode eingeben">
            </div>
        </div>
    </div>
</body>
</html>