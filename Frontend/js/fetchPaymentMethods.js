document.addEventListener('DOMContentLoaded', function () {
    const methodSelect = document.getElementById('method');
    const paymentDetails = document.getElementById('paymentDetails');

    // Zahlungsmethoden laden
    function loadPaymentMethods() {
        fetch('/Backend/logic/getPaymentMethodsAPI.php')
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Zahlungsmethoden in das Dropdown einfügen
                    methodSelect.innerHTML = data.paymentMethods
                        .map(
                            (method) => `
                            <option value="${method.id}" data-method="${method.method}">
                                ${method.method}
                            </option>
                        `
                        )
                        .join('');
                } else {
                    methodSelect.innerHTML = `<option disabled>${data.message}</option>`;
                }
            })
            .catch((error) => console.error('Fehler beim Laden der Zahlungsmethoden:', error));
    }

    // Details der ausgewählten Zahlungsmethode anzeigen
    methodSelect.addEventListener('change', function () {
        const selectedOption = methodSelect.options[methodSelect.selectedIndex];
        const methodId = selectedOption.value;
        const methodName = selectedOption.dataset.method;

        fetch(`/Backend/logic/getPaymentMethodsAPI.php`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const selectedMethod = data.paymentMethods.find((method) => method.id == methodId);

                    if (selectedMethod) {
                        paymentDetails.innerHTML = `
                            <p><strong>Methode:</strong> ${selectedMethod.method}</p>
                            ${
                                selectedMethod.method === 'Kreditkarte'
                                    ? `
                                    <p><strong>Kreditkartennummer:</strong> **** **** **** ${selectedMethod.card_number.slice(-4)}</p>
                                    <p><strong>Ablaufdatum:</strong> ${selectedMethod.expiry_date}</p>
                                    `
                                    : selectedMethod.method === 'Paypal'
                                    ? `<p><strong>PayPal E-Mail:</strong> ${selectedMethod.paypal_email}</p>`
                                    : selectedMethod.method === 'Klarna'
                                    ? `<p><strong>Klarna Konto:</strong> ${selectedMethod.klarna_account}</p>`
                                    : selectedMethod.method === 'Kauf auf Rechnung'
                                    ? `<p><strong>Rechnungsadresse:</strong> ${selectedMethod.billing_address}</p>`
                                    : selectedMethod.method === 'Apple Pay'
                                    ? `<p><strong>Apple Pay Token:</strong> ${selectedMethod.apple_pay_token}</p>`
                                    : `<p>Keine weiteren Details verfügbar.</p>`
                            }
                        `;
                    }
                }
            })
            .catch((error) => console.error('Fehler beim Abrufen der Zahlungsmethode:', error));
    });

    loadPaymentMethods();
});