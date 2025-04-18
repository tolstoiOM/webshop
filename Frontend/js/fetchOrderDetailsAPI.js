document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('orderId');

    const orderDetailsContainer = document.getElementById('orderDetails');

    if (!orderId) {
        orderDetailsContainer.innerHTML = '<div class="alert alert-danger">Bestell-ID fehlt.</div>';
        return;
    }

    // API-Aufruf, um Bestelldetails abzurufen
    fetch(`/Backend/logic/getOrderDetailsAPI.php?orderId=${orderId}`)
        .then((response) => response.json())
        .then((response) => {
            if (!response.success) {
                orderDetailsContainer.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
                return;
            }

            const order = response.order;
            const orderItems = response.orderItems;
            const isAdmin = response.isAdmin;

            let html = `
                <div class="card">
                    <div class="card-header">
                        <h4>Bestelldetails</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Bestellnummer:</strong> ${order.id}</p>
                        <p><strong>Datum:</strong> ${order.created_at}</p>
                        <p><strong>Gesamtbetrag:</strong> €${parseFloat(order.total_price).toFixed(2)}</p>
                        <h5 class="mt-4">Produkte</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produkt</th>
                                    <th>Menge</th>
                                    <th>Preis</th>
                                    <th>Gesamt</th>
                                    ${isAdmin ? '<th>Aktionen</th>' : ''}
                                </tr>
                            </thead>
                            <tbody>
            `;

            orderItems.forEach(item => {
                html += `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>€${parseFloat(item.price).toFixed(2)}</td>
                        <td>€${(item.quantity * item.price).toFixed(2)}</td>
                        ${isAdmin ? `
                        <td>
                            <button class="btn btn-danger btn-sm remove-item" data-order-id="${order.id}" data-product-id="${item.product_id}">Entfernen</button>
                        </td>
                        ` : ''}
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            orderDetailsContainer.innerHTML = html;

            // Event-Listener für die "Entfernen"-Buttons hinzufügen
            if (isAdmin) {
                document.querySelectorAll('.remove-item').forEach((button) => {
                    button.addEventListener('click', function () {
                        const orderId = this.dataset.orderId;
                        const productId = this.dataset.productId;

                        if (confirm('Möchten Sie dieses Produkt wirklich aus der Bestellung entfernen?')) {
                            fetch('/Backend/logic/deleteOrderItemAPI.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ order_id: orderId, product_id: productId }),
                            })
                                .then((response) => response.json())
                                .then((result) => {
                                    if (result.success) {
                                        alert(result.message);
                                        location.reload(); // Seite neu laden, um die Änderungen anzuzeigen
                                    } else {
                                        alert('Fehler: ' + result.message);
                                    }
                                })
                                .catch((error) => {
                                    console.error('Fehler beim Entfernen des Produkts:', error);
                                    alert('Fehler beim Entfernen des Produkts.');
                                });
                        }
                    });
                });
            }
        })
    .catch(() => {
        orderDetailsContainer.innerHTML = '<div class="alert alert-danger">Fehler beim Abrufen der Bestelldetails.</div>';
    });
});