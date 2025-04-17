<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');
    require_once '../config/config.php';

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        if ($action === 'getCategories') {
            // Kategorien abrufen
            $stmt = $pdo->query("SELECT * FROM categories");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit();
        } elseif ($action === 'getProducts') {
            // Produkte basierend auf der Kategorie abrufen
            $categoryId = $_GET['categoryId'];

            try {
            
                if ($categoryId === 'all') {
                    $stmt = $pdo->query("SELECT id, name, description, price, CONCAT('/Backend/productpictures/', SUBSTRING_INDEX(image_path, '/', -1)) AS image_path FROM products");
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $stmt = $pdo->prepare("SELECT id, name, description, price, CONCAT('/Backend/productpictures/', SUBSTRING_INDEX(image_path, '/', -1)) AS image_path FROM products WHERE category_id = ?");
                    $stmt->execute([$categoryId]);
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                echo json_encode(['success' => true, 'products' => $products]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        } elseif ($action === 'getCartCount') {
            // Anzahl der Produkte im Warenkorb berechnen
            $userId = $_SESSION['user_id'];
            $stmt = $pdo->prepare("SELECT SUM(quantity) AS cartCount FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);
            $cartCount = $stmt->fetchColumn();

            echo json_encode(['success' => true, 'cartCount' => $cartCount]);
            exit();
        } elseif ($action === 'getAllProducts') {
            // Produkte aus der Datenbank abrufen
            $sql = "SELECT id, name, description, price, CONCAT('/Backend/productpictures/', SUBSTRING_INDEX(image_path, '/', -1)) AS image_path FROM products";
            $result = $conn->query($sql);

            // Überprüfen, ob Produkte vorhanden sind
            $products = [];
            if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            }

            // JSON zurückgeben
            echo json_encode($products);
        }
    }

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'addToCart') {
            // Produkt zum Warenkorb hinzufügen
            $userId = $_SESSION['user_id'];
            $productId = $_POST['productId'];

            $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$userId, $productId]);
            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cartItem) {
                $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$userId, $productId]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
                $stmt->execute([$userId, $productId]);
            }

            $stmt = $pdo->prepare("SELECT SUM(quantity) AS cartCount FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);
            $cartCount = $stmt->fetchColumn();

            echo json_encode(['success' => true, 'cartCount' => $cartCount]);
            
            exit();
        } elseif ($action === 'removeFromCart') {
            // Produkt aus dem Warenkorb entfernen
            $userId = $_SESSION['user_id'];
            $productId = $_POST['productId'];

            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$userId, $productId]);

            $stmt = $pdo->prepare("SELECT SUM(quantity) AS cartCount FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);
            $cartCount = $stmt->fetchColumn();

            echo json_encode(['success' => true, 'cartCount' => $cartCount]);
            exit();
        }
    }
?>