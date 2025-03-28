<?php
// filepath: c:\xampp\htdocs\webshop\Backend\config\config.php

$host = 'localhost'; // Database host
$dbname = 'webshop'; // Database name
$username = 'bif1user'; // Database username
$password = 'marko123'; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}