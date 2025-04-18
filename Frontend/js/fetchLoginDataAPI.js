// Load saved credentials from cookies and auto-login if "Remember Login" is enabled
document.addEventListener('DOMContentLoaded', function () {
    const emailCookie = document.cookie.split('; ').find(row => row.startsWith('email='));
    const passwordCookie = document.cookie.split('; ').find(row => row.startsWith('password='));

    if (emailCookie && passwordCookie) {
        const email = emailCookie.split('=')[1];
        const password = passwordCookie.split('=')[1];

        // Auto-login if cookies are set
        fetch('/Backend/logic/loginDataAPI.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = '/index.php'; // Redirect to homepage
                }
            })
            .catch((error) => console.error('Fehler beim automatischen Anmelden:', error));
    }
});

document.getElementById('login-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    const identifier = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const rememberLogin = document.getElementById('remember-login').checked;

    fetch('/Backend/logic/getloginDataAPI.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ identifier, password }),
    })
        .then((response) => response.json())
        .then((data) => {
            const messageDiv = document.getElementById('login-message');
            if (data.success) {
                // Save credentials in cookies if "Remember Login" is checked
                if (rememberLogin) {
                    document.cookie = `identifier=${identifier}; path=/; max-age=604800; Secure`; // 7 days
                    document.cookie = `password=${password}; path=/; max-age=604800; Secure`; // 7 days
                } else {
                    // Clear cookies if "Remember Login" is unchecked
                    document.cookie = 'identifier=; path=/; max-age=0';
                    document.cookie = 'password=; path=/; max-age=0';
                }

                messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                window.location.href = '/index.php';
            } else {
                // Clear the form on failed login
                document.getElementById('email').value = '';
                document.getElementById('password').value = '';
                document.getElementById('remember-login').checked = false;

                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            const messageDiv = document.getElementById('login-message');
            messageDiv.innerHTML = `<div class="alert alert-danger">Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.</div>`;
        });
});