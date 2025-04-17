$(document).ready(function () {
    // Kategorien und Produkte laden
    loadCategories();
    loadProducts('all'); // Standardmäßig alle Produkte laden

    // Kategorien laden
    function loadCategories() {
        $.ajax({
            url: '../../Backend/logic/getProductsAPI.php',
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
            url: '../../Backend/logic/getProductsAPI.php',
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
                                    <div class="card mb-4" draggable="true" ondragstart="handleDragStart(event)" data-id="${product.id}">
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

    // Überprüfen, ob der Benutzer angemeldet ist
    $.ajax({
        url: '../../Backend/logic/getSessionStatus.php', // API zum Überprüfen des Login-Status
        method: 'GET',
        dataType: 'json', // Automatisch als JSON parsen
        success: function (sessionStatus) {
            if (sessionStatus.loggedIn) {
                // Nur wenn der Benutzer angemeldet ist, wird der Warenkorb-Counter aktualisiert
                updateCartCount();
            } else {
                console.log('Benutzer ist nicht angemeldet. Warenkorb-Counter wird nicht aktualisiert.');
            }
        },
        error: function () {
            console.error('Fehler beim Überprüfen des Login-Status.');
        }
    });

    // Produkt zum Warenkorb hinzufügen
    $(document).off('click', '.add-to-cart').on('click', '.add-to-cart', function () {
        const productId = $(this).data('id');

        // Check if the user is logged in
        $.ajax({
            url: '../../Backend/logic/getSessionStatus.php', // Check session status
            method: 'GET',
            dataType: 'json', // Automatically parse the response as JSON
            success: function (sessionStatus) {
                console.log('Parsed Response:', sessionStatus); // Log the parsed response

                if (sessionStatus.loggedIn) {
                    // User is logged in, proceed to add the product to the cart
                    $.ajax({
                        url: '../../Backend/logic/getProductsAPI.php',
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
                    window.location.href = '/Frontend/sites/login.php';
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
            url: '../../Backend/logic/getProductsAPI.php',
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

    function loadAllProducts() {
        $.ajax({
            url: '../../Backend/logic/getProductsAPI.php',
            method: 'GET',
            dataType: 'json',
            data: { action: 'getAllProducts' },
            success: function (products) {
                const container = $('#products');
                if (!products.length) {
                container.html('<p class="text-center">Keine Produkte gefunden.</p>');
                return;
                }
        
                products.forEach(product => {
                const html = `
                    <div class="col-md-4">
                        <div class="card mb-4" draggable="true" ondragstart="handleDragStart(event)" data-id="${product.id}">
                            <img src="${product.image_path}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text"><strong>Preis:</strong> ${product.price} €</p>
                                 <button class="btn btn-success add-to-cart" data-id="${product.id}">In den Warenkorb</button>
                            </div>
                        </div>
                    </div>
                `;
                container.append(html);
                });
            },
            error: function (xhr, status, error) {
                console.error('Fehler beim Laden der Produkte:', error);
                $('#products').html('<p class="text-danger text-center">Produkte konnten nicht geladen werden.</p>');
            }
            });
    }
});

// Warenkorb-Counter aktualisieren
function updateCartCount() {
    $.ajax({
        url: '../../Backend/logic/getProductsAPI.php',
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

function searchProducts() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const productsContainer = document.getElementById('products');

    // Beispiel: Produkte aus einer API laden und filtern
    fetch('/Backend/logic/getProductsAPI.php?action=getAllProducts')
        .then((response) => response.json())
        .then((products) => {
            productsContainer.innerHTML = ''; // Vorherige Produkte entfernen

            const filteredProducts = products.filter((product) =>
                product.name.toLowerCase().includes(searchInput)
            );

            filteredProducts.forEach((product) => {
                productsContainer.innerHTML += `
                    <div class="col-md-4">
                        <div class="card mb-4" draggable="true" ondragstart="handleDragStart(event)" data-id="${product.id}">
                            <img src="${product.image_path}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text"><strong>Preis:</strong> €${product.price}</p>
                                <button class="btn btn-success add-to-cart" data-id="${product.id}">In den Warenkorb</button>
                            </div>
                        </div>
                    </div>
                `;
            });
        })
        .catch((error) => console.error('Fehler beim Laden der Produkte:', error));
}

// Funktion, die beim Start des Dragging aufgerufen wird
function handleDragStart(event) {
    console.log("Drag gestartet:", event.target); // Debugging-Log
    const productId = event.target.closest('.card').getAttribute('data-id'); // Sucht die nächste Karte
    if (productId) {
        event.dataTransfer.setData('text/plain', productId); // Produkt-ID speichern
        console.log("Produkt-ID gesetzt:", productId);
    } else {
        console.error('Produkt-ID nicht gefunden.');
    }
}

// Funktion, die das Standardverhalten beim Dragging über das Drop-Ziel verhindert
function handleDragOver(event) {
    event.preventDefault(); // Erlaubt das Droppen
}

// Funktion, die beim Droppen aufgerufen wird
function handleDrop(event) {
    event.preventDefault();
    const productId = event.dataTransfer.getData('text/plain'); // Produkt-ID abrufen

    // AJAX-Request um das Produkt zum Warenkorb hinzuzufügen
    $.ajax({
        url: '../../Backend/logic/getProductsAPI.php',  // Ziel-URL
        method: 'POST',  // HTTP-Methode
        //contentType: 'application/json',  // Senden als JSON
        data: { action: 'addToCart', productId: productId },  // Daten
        success: function(data) {
            if (data.success) {
                updateCartCount();  // Warenkorb-Counter aktualisieren
                alert('Produkt wurde dem Warenkorb hinzugefügt!');
            } else {
                console.error('Fehler beim Hinzufügen zum Warenkorb:', data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Fehler beim Hinzufügen zum Warenkorb:', error);
        }
    });
}