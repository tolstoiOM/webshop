<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'getCategories') {
        // Alle Kategorien abrufen
        $stmt = $pdo->query("SELECT * FROM categories");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($action == 'getProducts') {
        // Produkte basierend auf der Kategorie abrufen
        $categoryId = $_GET['categoryId'];

        if ($categoryId === 'all') {
            // Alle Produkte abrufen, wenn "Alles" ausgewählt ist
            $stmt = $pdo->query("SELECT * FROM products");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            // Produkte einer bestimmten Kategorie abrufen
            $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
            $stmt->execute([$categoryId]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    } elseif ($action == 'searchProducts') {
        // Produkte basierend auf einer Suchanfrage abrufen
        $query = "%" . $_GET['query'] . "%";
        $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
        $stmt->execute([$query]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'addToCart') {
    // Hier wird der Warenkorb in einer Session gespeichert
    session_start();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $productId = $_POST['productId'];
    if (!in_array($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $productId;
    }
    echo json_encode(['cartCount' => count($_SESSION['cart'])]);

    if (isset($_POST['action']) && $_POST['action'] === 'removeFromCart') {
        session_start();
        $productId = $_POST['productId'];
    
        // Überprüfen, ob der Warenkorb existiert
        if (isset($_SESSION['cart']) && array_key_exists($productId, $_SESSION['cart'])) {
            // Produkt aus dem Warenkorb entfernen
            unset($_SESSION['cart'][$productId]);
        }
    
        // Anzahl der Produkte im Warenkorb berechnen
        $cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
    
        // JSON-Antwort zurückgeben
        echo json_encode(['success' => true, 'cartCount' => $cartCount]);
        exit();
    }
}
?>