<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Frontend/sites/orderDetails.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellung ansehen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Bestellung ansehen</h2>
        <div id="orderDetails" class="mt-4"></div>
        <!-- Link zur Startseite -->
        <div class="text-center mt-4">
            <a href="./index.php" class="btn btn-primary">Zurück zur Startseite</a>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const orderId = urlParams.get('orderId');

            if (!orderId) {
                $('#orderDetails').html('<div class="alert alert-danger">Bestell-ID fehlt.</div>');
                return;
            }

            // API-Aufruf, um Bestelldetails abzurufen
            $.getJSON('/webshop/Backend/logic/getOrderDetails.php', { orderId: orderId }, function (response) {
                if (!response.success) {
                    $('#orderDetails').html('<div class="alert alert-danger">' + response.message + '</div>');
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

                $('#orderDetails').html(html);
            }).fail(function () {
                $('#orderDetails').html('<div class="alert alert-danger">Fehler beim Abrufen der Bestelldetails.</div>');
            });
        });
    </script>
</body>
</html>