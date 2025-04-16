<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harry Potter Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <?php include '../sites/navbar.php'; ?>

    <div class="hero-image-shop">
        <div class="hero-text-shop text-center text-white">
            <h1>Finde deine Lieblingsprodukte!</h1>
        </div>
    </div>

    <div class="container mt-5">
        <div id="categories">
            <h3>Wählen Sie eine Kategorie:</h3>
            <select id="category-select" class="form-select mb-4">
                <!-- Kategorien werden hier durch JavaScript geladen -->
            </select>
        </div>


        <div id="search">
            <input type="text" id="search-input" class="form-control" placeholder="Suche nach Produkten..."
                onkeyup="searchProducts()">
        </div>

        <div id="products" class="row mt-4">
            <!-- Produkte werden hier durch JavaScript geladen -->
        </div>
    </div>

    <script src="../js/script.js"></script>

</body>

</html>