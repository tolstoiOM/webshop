document.addEventListener('DOMContentLoaded', function () {
    fetch('/Backend/logic/getUserDataAPI.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                alert(data.error);
                return;
            }

            const usersContainer = document.getElementById('users-container');
            let html = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Anrede</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>Adresse</th>
                            <th>PLZ</th>
                            <th>Stadt</th>
                            <th>E-Mail</th>
                            <th>Benutzername</th>
                            <th>Status</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.users.forEach((user) => {
                html += `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.salutation}</td>
                        <td>${user.first_name}</td>
                        <td>${user.last_name}</td>
                        <td>${user.address}</td>
                        <td>${user.zip}</td>
                        <td>${user.city}</td>
                        <td>${user.email}</td>
                        <td>${user.username}</td>
                        <td>
                            <select class="form-select user-status" data-id="${user.id}">
                                <option value="active" ${user.status === 'active' ? 'selected' : ''}>Aktiv</option>
                                <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inaktiv</option>
                            </select>
                        </td>
                        <td>
                            <a href="/Frontend/sites/myorders.php?user_id=${user.id}" class="btn btn-primary btn-sm">Bestellungen</a>
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            usersContainer.innerHTML = html;

            // Event-Listener für Statusänderungen hinzufügen
            document.querySelectorAll('.user-status').forEach((select) => {
                select.addEventListener('change', function () {
                    const userId = this.dataset.id;
                    const newStatus = this.value;

                    fetch('/Backend/logic/writeUserDataAPI.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ userId, status: newStatus }),
                    })
                        .then((response) => response.json())
                        .then((result) => {
                            if (result.success) {
                                alert('Status erfolgreich aktualisiert.');
                            } else {
                                alert('Fehler beim Aktualisieren des Status: ' + result.message);
                            }
                        })
                        .catch((error) => {
                            console.error('Fehler:', error);
                            alert('Fehler beim Aktualisieren des Status.');
                        });
                });
            });
        })
        .catch((error) => {
            console.error('Fehler beim Abrufen der Benutzerdaten:', error);
            alert('Fehler beim Laden der Benutzerdaten.');
        });
});