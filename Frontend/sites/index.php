<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Webshop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../res/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
  <!-- Navigation Bar -->
  <?php include '../sites/navbar.php'; ?>

  <!-- Hero Section -->
  <header class="bg-primary text-white text-center py-5">
    <h1>Welcome to My Webshop</h1>
    <p>Your one-stop shop for amazing products!</p>
  </header>

  <!-- Product Listing -->
  <section class="container my-5">
    <h2 class="text-center mb-4">Our Products</h2>
    <div class="row" id="product-container">
      <!-- Products will be loaded dynamically -->
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