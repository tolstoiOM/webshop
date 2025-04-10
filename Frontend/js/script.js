$(document).ready(function () {
    // Kategorien und Produkte laden
    loadCategories();
    loadProducts('all'); // Standardmäßig alle Produkte laden
    updateCartCount(); // Warenkorb-Counter beim Laden der Seite aktualisieren

    // Kategorien laden
    function loadCategories() {
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'getCategories' },
            success: function (data) { // Antwort ist bereits ein JSON-Objekt
                let categories = data; // Kein JSON.parse() notwendig
                let categorySelect = $('#category-select');

                categorySelect.append(`<option value="all">Alles</option>`);

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
            success: function (data) { // Antwort ist bereits ein JSON-Objekt
                if (data.success) {
                    let products = data.products;
                    let productContainer = $('#products');
                    productContainer.empty();

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
                                            <button class="btn btn-success add-to-cart" data-id="${product.id}">In den Warenkorb</button>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        productContainer.append('<p class="text-center">Keine Produkte verfügbar.</p>');
                    }
                } else {
                    console.error('Fehler in der Antwort:', data.message);
                }
            },
            error: function () {
                alert('Fehler beim Laden der Produkte.');
            }
        });
    }

    // Warenkorb-Counter aktualisieren
    function updateCartCount() {
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'getCartCount' },
            success: function (data) { // Antwort ist bereits ein JSON-Objekt
                if (data.success) {
                    $('#cart-count').text(data.cartCount);
                } else {
                    console.error('Fehler in der Antwort:', data.message);
                }
            },
            error: function () {
                console.error('Fehler beim Abrufen der Warenkorb-Anzahl.');
            }
        });
    }

    // Produkt zum Warenkorb hinzufügen
    $(document).off('click', '.add-to-cart').on('click', '.add-to-cart', function () {
        const productId = $(this).data('id');

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
                            if (data.success) {
                                updateCartCount();
                                alert('Produkt wurde dem Warenkorb hinzugefügt!');
                            } else {
                                console.error('Fehler in der Antwort:', data.message);
                            }
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

    // Produkt aus dem Warenkorb entfernen
    $(document).on('click', '.remove-from-cart', function () {
        const productId = $(this).data('id');
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'POST',
            data: { action: 'removeFromCart', productId: productId },
            success: function (data) { // Antwort ist bereits ein JSON-Objekt
                if (data.success) {
                    updateCartCount();
                    location.reload();
                } else {
                    console.error('Fehler in der Antwort:', data.message);
                }
            },
            error: function () {
                alert('Fehler beim Entfernen des Produkts.');
            }
        });
    });

    // Kategorienwechsel
    $('#category-select').change(function () {
        let categoryId = $(this).val();
        loadProducts(categoryId);
    });
});