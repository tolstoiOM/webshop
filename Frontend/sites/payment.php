<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/webshop/Frontend/sites/payment.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zahlung durchführen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include '../sites/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Zahlung durchführen</h2>
        <div class="card mt-4">
            <div class="card-header">
                <h4>Zahlungsdetails</h4>
            </div>
            <div class="card-body">
                <form id="paymentForm">
                    <div class="mb-3">
                        <label for="method" class="form-label">Zahlungsmethode</label>
                        <select class="form-select" id="method" name="method" required>
                            <option value="Kreditkarte">Kreditkarte</option>
                            <option value="Paypal">Paypal</option>
                        </select>
                    </div>
                    <div id="creditCardDetails" class="mb-3" style="display: none;">
                        <label for="cardNumber" class="form-label">Kreditkartennummer</label>
                        <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456">
                        <label for="expiryDate" class="form-label mt-3">Ablaufdatum</label>
                        <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY">
                        <label for="cvv" class="form-label mt-3">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123">
                    </div>
                    <div id="paypalDetails" class="mb-3" style="display: none;">
                        <label for="paypalEmail" class="form-label">PayPal E-Mail</label>
                        <input type="email" class="form-control" id="paypalEmail" name="paypalEmail" placeholder="example@email.com">
                    </div>
                    <div id="payment-error" class="text-danger mb-3" style="display: none;"></div>
                    <button type="button" id="submitPayment" class="btn btn-primary">Zahlung durchführen</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Zeige die entsprechenden Eingabefelder basierend auf der Zahlungsmethode
            $('#method').change(function () {
                const method = $(this).val();
                if (method === 'Kreditkarte') {
                    $('#creditCardDetails').show();
                    $('#paypalDetails').hide();
                } else if (method === 'Paypal') {
                    $('#creditCardDetails').hide();
                    $('#paypalDetails').show();
                }
            });

            // Zahlung durchführen
            $('#submitPayment').click(function () {
        const method = $('#method').val();
        const cartItems = [
            // Beispiel: Produkte im Warenkorb (dynamisch generieren)
            { product_id: 1, quantity: 2, price: 10.99 },
            { product_id: 2, quantity: 1, price: 15.49 }
        ];
        const totalAmount = 37.47; // Beispiel: Gesamtbetrag (dynamisch berechnen)

        $.ajax({
            url: '/webshop/Backend/logic/transaction.php',
            method: 'POST',
            data: {
                method,
                cartItems: JSON.stringify(cartItems),
                totalAmount
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    window.location.href = response.redirectUrl;
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Fehler: " + error);
                console.error("Antwort: " + xhr.responseText);
                alert("Ein Fehler ist aufgetreten: " + xhr.responseText);
            }
        });
    });
});
    </script>
</body>

</html>