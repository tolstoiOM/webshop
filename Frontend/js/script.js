$(document).ready(function() {
    // Produkte und Kategorien laden
    loadCategories();
    loadProducts();

    // Kategorien laden
    function loadCategories() {
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'getCategories' },
            success: function(data) {
                let categories = JSON.parse(data);
                let categorySelect = $('#category-select');
                categories.forEach(category => {
                    categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                });
            }
        });
    }

    // Produkte basierend auf der ausgewählten Kategorie laden
    function loadProducts() {
        let categoryId = $('#category-select').val();
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'getProducts', categoryId: categoryId },
            success: function(data) {
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

                // Warenkorb Funktionalität (AJAX)
                $('.add-to-cart').click(function() {
                    let productId = $(this).data('id');
                    $.ajax({
                        url: '../../Backend/logic/ProductLogic.php',
                        method: 'POST',
                        data: { action: 'addToCart', productId: productId },
                        success: function(data) {
                            let cartCount = JSON.parse(data).cartCount;
                            $('#cart-count').text(cartCount);
                        }
                    });
                });
            }
        });
    }

    // Kategorienwechsel: Lade die Produkte der neuen Kategorie
    $('#category-select').change(function() {
        loadProducts();
    });

    // Produktsuche
    function searchProducts() {
        let query = $('#search-input').val();
        $.ajax({
            url: '../../Backend/logic/ProductLogic.php',
            method: 'GET',
            data: { action: 'searchProducts', query: query },
            success: function(data) {
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
            }
        });
    }
});
