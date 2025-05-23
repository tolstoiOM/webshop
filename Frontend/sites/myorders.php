<?php
    require_once '../../Backend/config/session.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
    
    // Überprüfen, ob der Benutzer ein Administrator ist
    $isAdmin = ($_SESSION['role'] === 'administrator');

    // Überprüfen, ob eine user_id in der URL übergeben wurde
    $userId = $isAdmin && isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellungen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const userId = <?php echo $userId; ?>; // Übergibt die user_id an das JavaScript
    </script>
    <script src="../js/fetchOrdersAPI.js" defer></script>
    <script src="../js/fetchProductsAPI.js" defer></script>
</head>

<body>
    <?php include '../sites/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Bestellungen</h2>
            <!-- Platzhalter für die Bestellungen -->

        <div id="orders-container" class="mt-4">
            <!-- Dynamische Inhalte werden hier eingefügt -->
        </div>
    </div>
</body>
</html>