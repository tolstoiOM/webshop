<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Verbindung zur Datenbank herstellen
    require_once '../config/config.php';

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
    header('Content-Type: application/json');
    echo json_encode($products);
?>