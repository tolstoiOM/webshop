$(document).ready(function () {
    // Produkte und Kategorien laden
    loadCategories();
    loadProducts('all'); // Standardmäßig alle Produkte laden

    // Kategorien laden
    function loadCategories() {
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'getCategories' },
            success: function (data) {
                let categories = JSON.parse(data);
                let categorySelect = $('#category-select');

                // "Alles"-Kategorie hinzufügen
                categorySelect.append(`<option value="all">Alles</option>`);

                // Dynamisch geladene Kategorien hinzufügen
                categories.forEach(category => {
                    categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                });
            },
            error: function () {
                alert('Fehler beim Laden der Kategorien.');
            }
        });
    }

    // Produkte laden
    function loadProducts(categoryId) {
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'getProducts', categoryId: categoryId },
            success: function (data) {
                let products = JSON.parse(data);
                let productContainer = $('#products');
                productContainer.empty(); // Container leeren

                if (products.length > 0) {
                    products.forEach(product => {
                        productContainer.append(`
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <img src="${product.image_path}" class="card-img-top" alt="${product.name}">
                                    <div class="card-body">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text">${product.description}</p>
                                        <p class="card-text"><strong>Preis:</strong> €${product.price}</p>
                                        <a href="product.php?id=${product.id}" class="btn btn-primary">Details</a>
                                        <button class="btn btn-success add-to-cart" data-id="${product.id}">In den Warenkorb</button>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    // Warenkorb-Logik
                    $('.add-to-cart').click(function () {
                        let productId = $(this).data('id');

                        // Check if the user is logged in
                        $.ajax({
                            url: '../../Backend/logic/session_status.php', // Check session status
                            method: 'GET',
                            dataType: 'json', // Automatically parse the response as JSON
                            success: function (sessionStatus) {
                                console.log('Parsed Response:', sessionStatus); // Log the parsed response

                                if (sessionStatus.loggedIn) {
                                    // User is logged in, proceed to add the product to the cart
                                    $.ajax({
                                        url: '../../Backend/logic/ProductLogic.php',
                                        method: 'POST',
                                        data: { action: 'addToCart', productId: productId },
                                        success: function (data) {
                                            let response = JSON.parse(data);
                                            let cartCount = response.cartCount;
                                            $('#cart-count').text(cartCount); // Update cart count
                                            alert('Produkt wurde dem Warenkorb hinzugefügt!');
                                        },
                                        error: function () {
                                            alert('Fehler beim Hinzufügen zum Warenkorb.');
                                        }
                                    });
                                } else {
                                    // User is not logged in, redirect to the login page
                                    alert('Bitte melden Sie sich an, um Produkte in den Warenkorb zu legen.');
                                    window.location.href = '/webshop/Frontend/sites/login.php';
                                }
                            },
                            error: function () {
                                alert('Fehler beim Überprüfen des Login-Status.');
                            }
                        });
                    });
                } else {
                    productContainer.append('<p class="text-center">Keine Produkte verfügbar.</p>');
                }
            },
            error: function () {
                alert('Fehler beim Laden der Produkte.');
            }
        });
    }

    // Kategorienwechsel: Produkte basierend auf der ausgewählten Kategorie laden
    $('#category-select').change(function () {
        let categoryId = $(this).val();
        loadProducts(categoryId);
    });

    // Produktsuche
    function searchProducts() {
        let query = $('#search-input').val();
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'searchProducts', query: query },
            success: function (data) {
                let products = JSON.parse(data);
                $('#products').empty();
                products.forEach(product => {
                    $('#products').append(`
                        <div class="product">
                            <img src="${product.image_path}" alt="${product.name}">
                            <h4>${product.name}</h4>
                            <p>${product.description}</p>
                            <p>Preis: €${product.price}</p>
                            <p>Bewertung: ${product.rating}</p>
                            <button class="add-to-cart" data-id="${product.id}">In den Warenkorb legen</button>
                        </div>
                    `);
                });
            },
            error: function () {
                alert('Fehler beim Suchen der Produkte.');
            }
        });
    }

    // Prüfen, ob wir uns auf der index.php befinden
    if (window.location.pathname.includes('../Frontend/sites/index.php')) {
        loadProducts('all'); // Alle Produkte laden
    }
});