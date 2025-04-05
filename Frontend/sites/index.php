<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Verbindung zur Datenbank herstellen
require_once '../../Backend/config/config.php';

// Produkte aus der Datenbank abrufen
$sql = "SELECT id, name, description, price, image_path FROM products";
$result = $conn->query($sql);

// Überprüfen, ob Produkte vorhanden sind
$products = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Harry Potter Store</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../res/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../js/script.js" defer></script>

</head>

<body>
  <!-- Navigation Bar -->
  <?php include '../sites/navbar.php'; ?>

  <!-- Hero Section -->
  <header class="hero-image-home">
    <div class="hero-text-shop text-center text-white">
      <h1>Welcome to the Harry Potter Store</h1>
      <p>Get all your Harry Potter merchandise here</p>
    </div>
  </header>

  <!-- Product Listing -->
  <section class="container my-5">
    <h2 class="text-center mb-4">Our Products</h2>
    <div id="products" class="row">
      <!-- Produkte werden hier dynamisch geladen -->
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <p>
      © 2025 My Webshop |
      <a href="privacy.php" class="text-white">Privacy Policy</a>
    </p>
  </footer>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/script.js"></script>
</body>

</html>