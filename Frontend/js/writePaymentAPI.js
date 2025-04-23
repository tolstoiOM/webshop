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
    const couponCode = $('#couponCode').val();
    
    if (!method) {
        alert('Bitte wählen Sie eine Zahlungsmethode aus.');
        return;
    }

    $.ajax({
        url: '/Backend/logic/writePaymentAPI.php',
        method: 'POST',
        data: {
            method,
            coupon_code: couponCode
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
            alert("Ein Fehler ist aufgetreten: " + xhr.responseText);
        }
    });
    });
});