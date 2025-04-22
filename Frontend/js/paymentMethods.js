document.addEventListener('DOMContentLoaded', function () {
    const paymentMethodsContainer = document.getElementById('payment-methods-container');
    const paymentMethodForm = document.getElementById('payment-method-form');
    const paymentDetailsContainer = document.getElementById('payment-details-container');

    // Zahlungsmethoden laden
    function loadPaymentMethods() {
        fetch('/Backend/logic/getPaymentMethodsAPI.php')
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    paymentMethodsContainer.innerHTML = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Methode</th>
                                    <th>Details</th>
                                    <th>Hinzugefügt am</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.paymentMethods
                                    .map(
                                        (method) => `
                                        <tr>
                                            <td>${method.method}</td>
                                            <td>
                                                ${
                                                    method.method === 'Kreditkarte'
                                                        ? `Karte: **** **** **** ${method.card_number.slice(-4)}`
                                                        : method.method === 'Paypal'
                                                        ? `E-Mail: ${method.paypal_email}`
                                                        : method.method === 'Klarna'
                                                        ? `Konto: ${method.klarna_account}`
                                                        : method.method === 'Kauf auf Rechnung'
                                                        ? `Adresse: ${method.billing_address}`
                                                        : method.method === 'Apple Pay'
                                                        ? `Token: ${method.apple_pay_token}`
                                                        : ''
                                                }
                                            </td>
                                            <td>${method.created_at}</td>
                                        </tr>
                                    `
                                    )
                                    .join('')}
                            </tbody>
                        </table>
                    `;
                } else {
                    paymentMethodsContainer.innerHTML = `<p class="text-danger">${data.message}</p>`;
                }
            })
            .catch((error) => console.error('Fehler beim Laden der Zahlungsmethoden:', error));
    }

    // Dynamische Felder basierend auf der Zahlungsmethode anzeigen
    document.getElementById('method').addEventListener('change', function () {
        const method = this.value;
        paymentDetailsContainer.innerHTML = '';

        if (method === 'Kreditkarte') {
            paymentDetailsContainer.innerHTML = `
                <label for="card_number" class="form-label">Kreditkartennummer</label>
                <input type="text" class="form-control" id="card_number" name="card_number" required>
                <label for="expiry_date" class="form-label mt-3">Ablaufdatum (MM/YY)</label>
                <input type="text" class="form-control" id="expiry_date" name="expiry_date" required>
                <label for="cvv" class="form-label mt-3">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required>
            `;
        } else if (method === 'Paypal') {
            paymentDetailsContainer.innerHTML = `
                <label for="paypal_email" class="form-label">PayPal E-Mail</label>
                <input type="email" class="form-control" id="paypal_email" name="paypal_email" required>
            `;
        } else if (method === 'Klarna') {
            paymentDetailsContainer.innerHTML = `
                <label for="klarna_account" class="form-label">Klarna Konto</label>
                <input type="text" class="form-control" id="klarna_account" name="klarna_account" required>
            `;
        } else if (method === 'Kauf auf Rechnung') {
            paymentDetailsContainer.innerHTML = `
                <label for="billing_address" class="form-label">Rechnungsadresse</label>
                <input type="text" class="form-control" id="billing_address" name="billing_address" required>
            `;
        } else if (method === 'Apple Pay') {
            paymentDetailsContainer.innerHTML = `
                <label for="apple_pay_token" class="form-label">Apple Pay Token</label>
                <input type="text" class="form-control" id="apple_pay_token" name="apple_pay_token" required>
            `;
        }
    });

    // Neue Zahlungsmethode speichern
    paymentMethodForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(paymentMethodForm);
        const data = Object.fromEntries(formData.entries());

        fetch('/Backend/logic/savePaymentMethodsAPI.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
            .then((response) => response.json())
            .then((data) => {
                alert(data.message);
                if (data.success) {
                    loadPaymentMethods();
                    paymentMethodForm.reset();
                    paymentDetailsContainer.innerHTML = '';
                }
            })
            .catch((error) => console.error('Fehler beim Speichern der Zahlungsmethode:', error));
    });

    loadPaymentMethods();
});