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
    })
    .catch(() => {
        orderDetailsContainer.innerHTML = '<div class="alert alert-danger">Fehler beim Abrufen der Bestelldetails.</div>';
    });
});