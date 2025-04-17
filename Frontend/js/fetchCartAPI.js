document.addEventListener('DOMContentLoaded', function () {
    refreshCart();
});

// Funktion zum Aktualisieren der Warenkorb-Daten
function refreshCart() {
    fetch('/Backend/logic/getCartAPI.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                document.getElementById('cart-container').innerHTML = `
                    <div class="alert alert-warning text-center">${data.error}</div>
                `;
                return;
            }

            const cartItems = data.cartItems;
            const totalPrice = data.totalPrice;

            // Warenkorb-Daten in die Tabelle einfügen
            const tbody = document.getElementById('cart-items');
            tbody.innerHTML = ''; // Vorherige Inhalte löschen

            cartItems.forEach((item) => {
                const imagePath = '/' + item.image_path.replace(/^\/+/, ''); // Pfad bereinigen
                tbody.innerHTML += `
                    <tr>
                        <td>
                            <img src="/Backend/productpictures${imagePath}" alt="${item.name}" style="width: 50px; height: 50px;">
                            ${item.name}
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger decrease-quantity" data-id="${item.product_id}">-</button>
                            x${item.quantity}
                            <button class="btn btn-sm btn-success increase-quantity" data-id="${item.product_id}">+</button>
                        </td>
                        <td>€${parseFloat(item.price).toFixed(2)}</td>
                        <td>€${(item.price * item.quantity).toFixed(2)}</td>
                    </tr>
                `;
            });

            // Gesamtpreis anzeigen
            document.getElementById('total-price').innerText = `€${totalPrice}`;
        })
        .catch((error) => console.error('Error fetching cart data:', error));
}

// Event-Listener für die Buttons hinzufügen
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('increase-quantity')) {
        const productId = event.target.getAttribute('data-id');
        updateCart('addToCart', productId);
    } else if (event.target.classList.contains('decrease-quantity')) {
        const productId = event.target.getAttribute('data-id');
        updateCart('removeFromCart', productId);
    }
});

// Funktion zum Aktualisieren des Warenkorbs
function updateCart(action, productId) {
    $.ajax({
        url: '../../Backend/logic/getProductsAPI.php',
        method: 'POST',
        data: { action: action, productId: productId },
        success: function (data) {
            console.log("data: ", data);
            if (data.success) {
                updateCartCount(); // Warenkorb-Counter aktualisieren
                refreshCart(); // Warenkorb-Daten aktualisieren
            } else {
                console.error('Fehler beim Aktualisieren des Warenkorbs:', data.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Fehler beim Aktualisieren des Warenkorbs:', error);
            console.log("data: ", data);
        }
    });
}