<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Backend/logic/transaction.php

// Debugging aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Nicht autorisiert.']);
        exit();
    }

    $userId = $_SESSION['user_id'];
    $method = $_POST['method'];
    $cartItems = json_decode($_POST['cartItems'], true); // JSON-Daten decodieren
    $totalAmount = $_POST['totalAmount']; // Gesamtbetrag der Bestellung

    require_once '../config/config.php';

    // Validierung der Zahlungsmethode
    if ($method !== 'Kreditkarte' && $method !== 'Paypal') {
        echo json_encode(['success' => false, 'message' => 'Ungültige Zahlungsmethode.']);
        exit();
    }

    try {
        // Bestellung in der Tabelle `orders` speichern
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$userId, $totalAmount]);
        $orderId = $pdo->lastInsertId(); // ID der neu erstellten Bestellung

        // Produkte in der Tabelle `order_items` speichern
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            // Debugging: Überprüfen der Produktdaten
            error_log("Produkt-ID: " . $item['product_id']);
            error_log("Menge: " . $item['quantity']);
            error_log("Preis: " . $item['price']);

            if (!$stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']])) {
                error_log("Fehler beim Einfügen in order_items: " . print_r($stmt->errorInfo(), true));
            }
        }

        // Status der Bestellung auf "completed" setzen
        $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
        $stmt->execute([$orderId]);

        // Weiterleitung zur Bestellübersicht
        echo json_encode(['success' => true, 'redirectUrl' => '/webshop/Backend/logic/generateInvoice.php?orderId=' . $orderId]);
        exit();
    } catch (Exception $e) {
        error_log("Fehler: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Fehler beim Speichern der Bestellung: ' . $e->getMessage()]);
        exit();
    }
}