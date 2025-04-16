document.addEventListener('DOMContentLoaded', function () {
    fetch('/Backend/logic/getOrdersAPI.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((data) => {
            const ordersContainer = document.getElementById('orders-container');
            if (data.error) {
                ordersContainer.innerHTML = `
                    <div class="alert alert-danger text-center">${data.error}</div>
                `;
                return;
            }

            const orders = data.orders;

            if (orders.length === 0) {
                ordersContainer.innerHTML = `
                    <p class="text-center">Keine Bestellungen gefunden.</p>
                `;
                return;
            }

            // Tabelle erstellen
            let tableHTML = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bestellnummer</th>
                            <th>Datum</th>
                            <th>Status</th>
                            <th>Gesamtbetrag</th>
                            <th>Details</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            orders.forEach(order => {
                tableHTML += `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.created_at}</td>
                        <td>${order.status}</td>
                        <td>€${parseFloat(order.total_price).toFixed(2)}</td>
                        <td>
                            <a href="/Frontend/sites/orderDetails.php?orderId=${order.id}" class="btn btn-sm btn-secondary">Ansehen</a>
                        </td>
                        <td>
                            <a href="?deleteOrderId=${order.id}" class="btn btn-sm btn-danger" onclick="return confirm('Möchten Sie diese Bestellung wirklich stornieren?');">Stornieren</a>
                        </td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            ordersContainer.innerHTML = tableHTML;
        })
        .catch((error) => {
            console.error('Error fetching orders:', error);
            const ordersContainer = document.getElementById('orders-container');
            ordersContainer.innerHTML = `
                <div class="alert alert-danger text-center">Fehler beim Laden der Bestellungen. Bitte versuchen Sie es später erneut.</div>
            `;
        });
});