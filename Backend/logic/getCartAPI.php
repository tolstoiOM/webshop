<?php
    require_once '../config/session.php';
    header('Content-Type: application/json');

    if (isset($_SESSION['user_id'])) {
        // Eingeloggter Benutzer: Warenkorb aus der Datenbank laden
        require_once '../config/config.php';

        $userId = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.name, p.price, p.image_path 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = ?");
        $stmt->execute([$userId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Nicht eingeloggter Benutzer: Warenkorb aus der Session laden
        $cartItems = [];
        if (isset($_SESSION['cart'])) {
            require_once '../config/config.php';
            foreach ($_SESSION['cart'] as $item) {
                $stmt = $pdo->prepare("SELECT id AS product_id, name, price, image_path FROM products WHERE id = ?");
                $stmt->execute([$item['productId']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($product) {
                    $product['quantity'] = $item['quantity'];
                    $cartItems[] = $product;
                }
            }
        }
    }

    // Gesamtpreis berechnen
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }

    // JSON-Antwort zurückgeben
    echo json_encode([
        'cartItems' => $cartItems,
        'totalPrice' => number_format($totalPrice, 2)
    ]);
?>