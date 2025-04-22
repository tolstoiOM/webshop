document.addEventListener('DOMContentLoaded', function () {
    loadProducts();

    // Produkte laden
    function loadProducts() {
        fetch('/Backend/logic/adminProductsAPI.php')
            .then((response) => response.json())
            .then((products) => {
                const container = document.getElementById('products-container');
                container.innerHTML = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Beschreibung</th>
                                <th>Preis</th>
                                <th>Bewertung</th>
                                <th>Kategorie</th>
                                <th>Bild</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${products
                                .map(
                                    (product) => `
                                <tr>
                                    <td>${product.name}</td>
                                    <td>${product.description}</td>
                                    <td>€${product.price}</td>
                                    <td>${product.rating || ''}</td>
                                    <td>${product.category_name}</td>
                                    <td><img src="${product.image_path}" alt="${product.name}" style="width: 50px;"></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-product" data-id="${product.id}">Bearbeiten</button>
                                        <button class="btn btn-danger btn-sm delete-product" data-id="${product.id}">Löschen</button>
                                    </td>
                                </tr>
                            `
                                )
                                .join('')}
                        </tbody>
                    </table>
                `;
            });
    }

    // Bearbeiten-Button-Event
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('edit-product')) {
            const productId = event.target.dataset.id;

            // Daten des Produkts holen
            fetch('/Backend/logic/adminProductsAPI.php')
                .then((response) => response.json())
                .then((products) => {
                    const product = products.find(p => p.id === productId || p.id === parseInt(productId));
                    if (product) {
                        // Formular mit den Daten befüllen
                        document.getElementById('form-title').textContent = 'Produkt bearbeiten';
                        document.getElementById('product-id').value = product.id;
                        document.getElementById('product-name').value = product.name;
                        document.getElementById('product-description').value = product.description;
                        document.getElementById('product-price').value = product.price;
                        document.getElementById('product-rating').value = product.rating || '';
                        document.getElementById('product-category').value = product.category_id;
                        // Bild bleibt leer, weil der File-Input aus Sicherheitsgründen nicht vorgefüllt werden kann
                    }
                });
        }
    });

    // Produkt hinzufügen/bearbeiten (Formular wird gesendet)
    document.getElementById('product-form').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('/Backend/logic/adminProductsAPI.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((result) => {
                alert(result.message);
                loadProducts();
                this.reset();

                // Zurück in den „Neues Produkt“-Modus
                document.getElementById('form-title').textContent = 'Neues Produkt hinzufügen';
                document.getElementById('product-id').value = '';
            })
            .catch((error) => console.error('Fehler beim Speichern des Produkts:', error));
    });

    // Produkt löschen
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-product')) {
            const productId = event.target.dataset.id;

            fetch('/Backend/logic/adminProductsAPI.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: productId }),
            })
                .then((response) => response.json())
                .then((result) => {
                    alert(result.message);
                    loadProducts();
                });
        }
    });
});