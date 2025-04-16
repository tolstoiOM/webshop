<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');

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
    echo json_encode($products);
?>