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

</head>

<body>
  <!-- Navigation Bar -->
  <?php include '../sites/navbar.php'; ?>

  <!-- Hero Section -->
  <header class="bg-primary text-white text-center py-5 hero-image">
    <h1 class="display-4">Welcome to the Harry Potter Store</h1>
    <p class="lead">Get all your Harry Potter merchandise here</p>
  </header>

  <!-- Product Listing -->
  <section class="container my-5">
    <h2 class="text-center mb-4">Our Products</h2>
    <div class="row" id="product-container">
    <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="card-text"><strong>Preis:</strong> €<?php echo htmlspecialchars($product['price']); ?></p>
                                <a href="product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-primary">Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Keine Produkte verfügbar.</p>
            <?php endif; ?>
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