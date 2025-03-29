<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'getCategories') {
        $stmt = $pdo->query("SELECT * FROM categories");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($action == 'getProducts') {
        $categoryId = $_GET['categoryId'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($action == 'searchProducts') {
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
}
?>