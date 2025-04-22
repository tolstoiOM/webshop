<?php
    require_once '../../Backend/config/session.php';

    // Überprüfen, ob der Benutzer eingeloggt und ein Admin ist
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
        header('Location: /index.php');
        exit();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Produkte verwalten</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../res/css/style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../js/adminProductsAPI.js" defer></script>
    </head>
    <body>
        <?php include '../sites/navbar.php'; ?>

        <div class="container mt-5">
            <h1 class="text-center">Produkte verwalten</h1>

            <!-- Produktliste -->
            <div id="products-container" class="mt-4">
                <!-- Produkte werden hier dynamisch geladen -->
            </div>

            <!-- Formular zum Hinzufügen/Bearbeiten von Produkten -->
            <div class="mt-5">
                <h2 id="form-title">Neues Produkt hinzufügen</h2>
                <form id="product-form">
                    <input type="hidden" id="product-id" name="id">
                    <div class="mb-3">
                        <label for="product-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="product-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-description" class="form-label">Beschreibung</label>
                        <textarea class="form-control" id="product-description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="product-price" class="form-label">Preis</label>
                        <input type="number" class="form-control" id="product-price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-rating" class="form-label">Bewertung</label>
                        <input type="text" class="form-control" id="product-rating" name="rating">
                    </div>
                    <div class="mb-3">
                        <label for="product-category" class="form-label">Kategorie</label>
                        <select class="form-select" id="product-category" name="category_id" required>
                            <option value="1">Bücher</option>
                            <option value="2">Kleidung</option>
                            <option value="3">Accessoires</option>
                            <option value="4">Spielzeug</option>
                            <option value="5">Dekoration</option>
                            <option value="6">Schmuck</option>
                            <option value="7">Geschenkideen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product-image" class="form-label">Bild</label>
                        <input type="file" class="form-control" id="product-image" name="image_path">
                    </div>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </form>
            </div>
        </div>
    </body>
</html>