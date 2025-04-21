<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');

    // Eingabedaten aus der Anfrage lesen
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['productId'], $data['action'])) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage.']);
        exit();
    }

    $productId = intval($data['productId']);
    $action = $data['action'];

    // Produkt in der Session speichern
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['productId'] === $productId) {
            if ($action === 'addToCart') {
                $item['quantity'] += 1;
            } elseif ($action === 'removeFromCart') {
                $item['quantity'] -= 1;
                if ($item['quantity'] <= 0) {
                    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($i) use ($productId) {
                        return $i['productId'] !== $productId;
                    });
                }
            }            
            $found = true;
            break;
        }
    }

    if (!$found && $action === 'addToCart') {
        $_SESSION['cart'][] = ['productId' => $productId, 'quantity' => 1];
    }


    echo json_encode(['success' => true, 'message' => 'Warenkorb aktualisiert.']);
    exit();
?>