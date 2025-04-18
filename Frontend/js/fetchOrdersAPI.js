document.addEventListener('DOMContentLoaded', function () {
    fetch('/Backend/logic/getOrdersAPI.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ user_id: userId }), // user_id an die API senden
    })
        .then((response) => response.json())
        .then((data) => {
            const ordersContainer = document.getElementById('orders-container');
            if (data.error) {
                ordersContainer.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                return;
            }

            if (data.orders.length === 0) {
                ordersContainer.innerHTML = `<div class="alert alert-info">Keine Bestellungen gefunden.</div>`;
                return;
            }

            let html = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bestellnummer</th>
                            <th>Gesamtpreis</th>
                            <th>Status</th>
                            <th>Erstellt am</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.orders.forEach((order) => {
                html += `
                    <tr>
                        <td>${order.id}</td>
                        <td>€${parseFloat(order.total_price).toFixed(2)}</td>
                        <td>${order.status}</td>
                        <td>${new Date(order.created_at).toLocaleDateString()}</td>
                        <td>
                            <button class="btn btn-info btn-sm view-details" data-id="${order.id}">Details</button>
                            <button class="btn btn-danger btn-sm cancel-order" data-id="${order.id}">Stornieren</button>
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            ordersContainer.innerHTML = html;

            // Event-Listener für die Detail-Buttons hinzufügen
            document.querySelectorAll('.view-details').forEach((button) => {
                button.addEventListener('click', function () {
                    const orderId = this.dataset.id;
                    window.location.href = `/Frontend/sites/orderDetails.php?orderId=${orderId}`;
                });
            });

            // Event-Listener für die Stornieren-Buttons hinzufügen
            document.querySelectorAll('.cancel-order').forEach((button) => {
                button.addEventListener('click', function () {
                    const orderId = this.dataset.id;
                    if (confirm(`Möchten Sie die Bestellung ${orderId} wirklich stornieren?`)) {
                        fetch('/Backend/logic/deleteOrderAPI.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ order_id: orderId }),
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
                                console.error('Fehler beim Stornieren der Bestellung:', error);
                                alert('Fehler beim Stornieren der Bestellung.');
                            });
                    }
                });
            });
        })
        .catch((error) => {
            console.error('Fehler beim Abrufen der Bestellungen:', error);
            const ordersContainer = document.getElementById('orders-container');
            ordersContainer.innerHTML = `<div class="alert alert-danger">Fehler beim Laden der Bestellungen.</div>`;
        });
});