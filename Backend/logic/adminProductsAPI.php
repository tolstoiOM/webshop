<?php
    require_once '../../Backend/config/session.php';
    require_once '../models/product.php';
    require_once '../config/config.php';

    header('Content-Type: application/json');

    // Nur Admins dürfen diese API verwenden
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
        echo json_encode(['success' => false, 'message' => 'Zugriff verweigert.']);
        exit();
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // Alle Produkte abrufen
        $product = new Product($pdo);
        echo json_encode($product->getAllProducts());
        
    } elseif ($method === 'POST') {
        if (!isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['category_id'])) {
            echo json_encode(['success' => false, 'message' => 'Alle erforderlichen Felder müssen ausgefüllt werden.']);
            exit();
        }
    
        $imageName = null;
        if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../Backend/productpictures/';
            $imageName = basename($_FILES['image_path']['name']);
            $targetPath = $uploadDir . $imageName;
            move_uploaded_file($_FILES['image_path']['tmp_name'], $targetPath);
        }

        $product = new Product($pdo);

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'rating' => $_POST['rating'] ?? '',
            'category_id' => $_POST['category_id'],
            'image_path' => $imageName,
        ];
    
        if (!empty($_POST['id'])) {
            $data['id'] = $_POST['id'];
            echo json_encode($product->updateProduct($data));
        } else {
            echo json_encode($product->createProduct($data));
        }

    } elseif ($method === 'DELETE') {
        // Produkt löschen
        $data = json_decode(file_get_contents('php://input'), true);
        $product = new Product($pdo);
        echo json_encode($product->deleteProduct($data['id']));
    }
?>