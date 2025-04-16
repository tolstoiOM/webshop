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
    url: '/Backend/logic/writePaymentAPI.php',
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