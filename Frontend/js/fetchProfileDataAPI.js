document.addEventListener('DOMContentLoaded', function () {
    fetch('/Backend/logic/getProfileDataAPI.php', {
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

            const user = data.user;

            // Felder mit Benutzerdaten füllen
            document.getElementById('salutation').textContent = user.salutation;
            document.getElementById('first_name').textContent = user.first_name;
            document.getElementById('last_name').textContent = user.last_name;
            document.getElementById('address').textContent = '****';
            document.getElementById('zip').textContent = '****';
            document.getElementById('city').textContent = '****';
            document.getElementById('email').textContent = '****';
            document.getElementById('username').textContent = user.username;

            // Originalwerte für die "Anzeigen"-Buttons speichern
            document.getElementById('address').dataset.original = user.address;
            document.getElementById('zip').dataset.original = user.zip;
            document.getElementById('city').dataset.original = user.city;
            document.getElementById('email').dataset.original = user.email;
        })
        .catch((error) => {
            console.error('Fehler beim Abrufen der Profildaten:', error);
            alert('Fehler beim Laden der Profildaten.');
        });

    // Event-Listener für "Anzeigen"-Buttons
    document.querySelectorAll('.toggle-visibility').forEach((button) => {
        button.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const targetElement = document.getElementById(targetId);
            const originalValue = targetElement.dataset.original;

            if (this.textContent === 'Anzeigen') {
                targetElement.textContent = originalValue;
                this.textContent = 'Verbergen';
            } else {
                targetElement.textContent = '****';
                this.textContent = 'Anzeigen';
            }
        });
    });
});