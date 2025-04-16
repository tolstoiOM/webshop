<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Nicht autorisiert.']);
            exit();
        }

        $userId = $_SESSION['user_id'];
        $method = $_POST['method'];

        require_once '../config/config.php';

        // Validierung der Zahlungsmethode
        if ($method !== 'Kreditkarte' && $method !== 'Paypal') {
            echo json_encode(['success' => false, 'message' => 'Ungültige Zahlungsmethode.']);
            exit();
        }

        try {
            // Produkte aus der `cart`-Tabelle abrufen
            $stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
            $stmt->execute([$userId]);
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($cartItems)) {
                throw new Exception("Der Warenkorb ist leer.");
            }

            // Gesamtbetrag berechnen
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Bestellung in der Tabelle `orders` speichern
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$userId, $totalAmount]);
            $orderId = $pdo->lastInsertId(); // ID der neu erstellten Bestellung

            // Produkte in der Tabelle `order_items` speichern
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                if (!$stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']])) {
                    error_log("Fehler beim Einfügen in order_items: " . print_r($stmt->errorInfo(), true));
                }
            }

            // Produkte aus der `cart`-Tabelle löschen
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);

            // Status der Bestellung auf "completed" setzen
            $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
            $stmt->execute([$orderId]);

            // JSON-Antwort mit Weiterleitungs-URL zurückgeben
            echo json_encode(['success' => true, 'redirectUrl' => '/Frontend/sites/orderDetails.php?orderId=' . $orderId]);
            exit();
        } catch (Exception $e) {
            error_log("Fehler: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Fehler beim Speichern der Bestellung: ' . $e->getMessage()]);
            exit();
        }
    }
?>