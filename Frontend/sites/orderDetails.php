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
        <title>Bestellung ansehen</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../res/css/style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../js/fetchOrderDetailsAPI.js" defer></script>
        <script src="../js/fetchProductsAPI.js" defer></script>
    </head>

    <body>

        <?php include '../sites/navbar.php'; ?>

        <div class="container mt-5">
            <h2 class="text-center">Bestellung ansehen</h2>
            <div id="orderDetails" class="mt-4"></div>
            <!-- Link zur Startseite -->
            <div class="text-center mt-4">
                <a href="/index.php" class="btn btn-primary">Zurück zur Startseite</a>
            </div>
        </div>
    </body>
</html>