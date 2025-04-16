// Alle Produkte laden
$(document).ready(function () {
    
    $.ajax({
        url: '../../Backend/logic/getProductsAPI.php',
        method: 'GET',
        dataType: 'json',
        success: function (products) {
            const container = $('#products');
            if (!products.length) {
            container.html('<p class="text-center">Keine Produkte gefunden.</p>');
            return;
            }
    
            products.forEach(product => {
            const html = `
                <div class="col-md-4">
                    <div class="card mb-4">
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
});