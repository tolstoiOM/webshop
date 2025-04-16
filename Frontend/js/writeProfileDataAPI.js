document.addEventListener('DOMContentLoaded', function () {
    const registrationForm = document.getElementById('registration-form');
    if (registrationForm) {
      registrationForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    // Validate passwords match
    if (data.password !== data.confirmPassword) {
      const messageDiv = document.getElementById('registration-message');
      messageDiv.innerHTML = `<div class="alert alert-danger">Passwörter stimmen nicht überein.</div>`;
      return;
    }

    fetch('/Backend/logic/writeProfileDataAPI.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((result) => {
        const messageDiv = document.getElementById('registration-message');
        if (result.success) {
          messageDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
          setTimeout(() => {
            window.location.href = '/Frontend/sites/login.php';
          }, 2000); // Redirect after 2 seconds
        } else {
          messageDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
        }
      })
      .catch((error) => {
        console.error('Error:', error);
        const messageDiv = document.getElementById('registration-message');
        messageDiv.innerHTML = `<div class="alert alert-danger">Ein Fehler ist aufgetreten. Bitte versuchen Sie es später noch einmal.</div>`;
      });
  });
} else {
    console.error('Das Element mit der ID "registration-form" wurde nicht gefunden.');
  }
});