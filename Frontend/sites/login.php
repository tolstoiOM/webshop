<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Harry Potter Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

    <?php include '../sites/navbar.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember-login">
                                <label class="form-check-label" for="remember-login">Remember Login</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <div id="login-message" class="mt-3"></div>
                    </div>
                    <div class="card-footer text-center">
                        <p>Don't have an account? <a href="registration.php">Register here</a></p>
                    </div>
                </div>
            </div>

            <script>
                // Load saved credentials if "Remember Login" was checked
                document.addEventListener('DOMContentLoaded', function () {
                    const savedEmail = localStorage.getItem('email');
                    const savedPassword = localStorage.getItem('password');
                    const rememberLogin = localStorage.getItem('rememberLogin') === 'true';

                    if (rememberLogin) {
                        document.getElementById('email').value = savedEmail || '';
                        document.getElementById('password').value = savedPassword || '';
                        document.getElementById('remember-login').checked = true;
                    }
                });

                document.getElementById('login-form').addEventListener('submit', function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const rememberLogin = document.getElementById('remember-login').checked;

                    fetch('/webshop/Backend/logic/login_process.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ email, password }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            const messageDiv = document.getElementById('login-message');
                            if (data.success) {
                                // Save credentials if "Remember Login" is checked
                                if (rememberLogin) {
                                    localStorage.setItem('email', email);
                                    localStorage.setItem('password', password);
                                    localStorage.setItem('rememberLogin', true);
                                } else {
                                    // Clear saved credentials if "Remember Login" is unchecked
                                    localStorage.removeItem('email');
                                    localStorage.removeItem('password');
                                    localStorage.removeItem('rememberLogin');
                                }

                                messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                                window.location.href = '/webshop/Frontend/sites/index.php';
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
                            messageDiv.innerHTML = `<div class="alert alert-danger">An error occurred. Please try again later.</div>`;
                        });
                });
            </script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>