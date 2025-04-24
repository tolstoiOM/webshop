<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Nicht autorisiert.']);
            exit();
        }

        $userId = $_SESSION['user_id'];
        $method = $_POST['method'];
        $couponCode = $_POST['coupon_code'] ?? null;

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

            // Gutscheincode prüfen
            $discount = 0;
            if ($couponCode) {
                $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND cashed = 0 AND expires_at > NOW()");
                $stmt->execute([$couponCode]);
                $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($coupon) {
                    $discount = min($totalAmount, $coupon['residual_value'] ?? $coupon['value']);
                    $totalAmount -= $discount;

                    // Restwert berechnen
                    $residualValue = ($coupon['residual_value'] ?? $coupon['value']) - $discount;

                    // Gutschein aktualisieren
                    $stmt = $pdo->prepare("UPDATE coupons SET residual_value = ?, cashed = ? WHERE id = ?");
                    $stmt->execute([$residualValue, $residualValue <= 0 ? 1 : 0, $coupon['id']]);
                } else {
                    throw new Exception("Ungültiger oder abgelaufener Gutscheincode.");
                }
            }

            // Bestellung in der Tabelle `orders` speichern
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, payment_method, status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$userId, $totalAmount, $method]);
            $orderId = $pdo->lastInsertId(); // ID der neu erstellten Bestellung

            // Rechnungsnummer generieren
            $invoiceNumber = sprintf('INV-%06d', $orderId);

            // Rechnungsnummer in der Bestellung speichern
            $stmt = $pdo->prepare("UPDATE orders SET invoice_number = ? WHERE id = ?");
            $stmt->execute([$invoiceNumber, $orderId]);

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