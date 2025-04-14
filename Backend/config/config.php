<?php
    $host = getenv('DB_HOST') ?: 'localhost'; // Database host
    $dbname = 'webshop'; // Database name
    $username = 'webprojectuser'; // Database username
    $password = 'xSnsN)F3!wg[vbPk'; // Database password


    // Verbindung herstellen
    $conn = new mysqli($host, $username, $password, $dbname);

    // Verbindung prüfen
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
?>