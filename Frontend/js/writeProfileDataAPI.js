document.addEventListener('DOMContentLoaded', function () {
    const saveChangesButton = document.getElementById('saveChanges');
    const errorMessage = document.getElementById('error-message');

    saveChangesButton.addEventListener('click', function () {

        const field = document.getElementById('field').value;
        const newValue = document.getElementById('newValue').value;
        const userId = document.getElementById('user_id').value;
        const password = document.getElementById('password').value;

        // Fehlerbereich zurücksetzen
        errorMessage.style.display = 'none';
        errorMessage.textContent = '';

        // Validierung der Eingabefelder
        if (!field || !newValue || !password) {
            errorMessage.textContent = 'Bitte füllen Sie alle Felder aus.';
            errorMessage.style.display = 'block';
            return;
        }

        fetch('/Backend/logic/updateProfileDataAPI.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                field: field,
                newValue: newValue,
                password: password,
                user_id: userId,
            }),
        })
            .then((response) => response.json())
            .then((response) => {
                if (response.success) {
                    alert(response.message);
                    location.reload(); // Seite neu laden, um die Änderungen anzuzeigen
                } else {
                    errorMessage.textContent = response.message;
                    errorMessage.style.display = 'block';
                }
            })
            .catch((error) => {
                errorMessage.textContent = 'Fehler beim Aktualisieren der Daten.';
                errorMessage.style.display = 'block';
            });
    });
});