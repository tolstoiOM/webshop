document.addEventListener('DOMContentLoaded', function () {
    loadCoupons();

    // Gutscheine laden
    function loadCoupons() {
        fetch('/Backend/logic/adminCouponAPI.php')
            .then((response) => response.json())
            .then((data) => {
                const container = document.getElementById('coupons-container');
                if (data.success) {
                    container.innerHTML = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Geldwert (€)</th>
                                    <th>Erstelldatum</th>
                                    <th>Ablaufdatum</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.coupons
                                    .map(
                                        (coupon) => `
                                        <tr>
                                            <td>${coupon.code}</td>
                                            <td>${coupon.value}</td>
                                            <td>${coupon.created_at}</td>
                                            <td>${coupon.expires_at}</td>
                                            <td>${coupon.status}</td>
                                        </tr>
                                    `
                                    )
                                    .join('')}
                            </tbody>
                        </table>
                    `;
                } else {
                    container.innerHTML = `<p class="text-danger">${data.message}</p>`;
                }
            })
            .catch((error) => console.error('Fehler beim Laden der Gutscheine:', error));
    }

    // Neuen Gutschein erstellen
    document.getElementById('coupon-form').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('/Backend/logic/adminCouponAPI.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                alert(data.message);
                loadCoupons();
                this.reset();
            })
            .catch((error) => console.error('Fehler beim Erstellen des Gutscheins:', error));
    });
});