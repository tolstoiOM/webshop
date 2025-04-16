document.addEventListener('DOMContentLoaded', function () {
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
                        <td>x${item.quantity}</td>
                        <td>€${parseFloat(item.price).toFixed(2)}</td>
                        <td>€${(item.price * item.quantity).toFixed(2)}</td>
                    </tr>
                `;
            });

            // Gesamtpreis anzeigen
            document.getElementById('total-price').innerText = `€${totalPrice}`;
        })
        .catch((error) => console.error('Error fetching cart data:', error));
});