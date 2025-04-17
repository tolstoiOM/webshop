<?php
  require_once 'Backend/config/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Harry Potter Shop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="Frontend/res/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="Frontend/js/fetchProductsAPI.js" defer></script>
</head>

<body>
  <!-- Navigation Bar -->
  <?php include 'Frontend/sites/navbar.php';?>

  <!-- Hero Section -->
  <header class="hero-image-home">
    <div class="hero-text-shop text-center text-white">
      <h1>Willkommen im Harry Potter Shop</h1>
      <p>Hier finden Sie alle Ihre Harry-Potter-Fanartikel!</p>
    </div>
  </header>

  <!-- Product Listing -->
  <section class="container my-5">
    <h2 class="text-center mb-4">Unsere Produkte</h2>
    <div id="products" class="row">
      <!-- Produkte werden hier dynamisch geladen -->
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <p>
      © 2025 Harry Potter Shop |
      <a href="privacy.php" class="text-white">Privacy Policy</a>
    </p>
  </footer>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="Frontend/js/fetchProductsAPI.js"></script>
</body>

</html>