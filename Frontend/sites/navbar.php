<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand">
            <img src="/Frontend/res/img/logo.png" alt="Harry Potter Shop" height="50" />
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Icons and Login/Logout Button -->
        <div class="d-flex align-items-center" id="navbar-icons">
            <!-- Icons will be dynamically loaded here -->
        </div>
    </div>
</nav>

<script>
    // Fetch user session status via AJAX
    document.addEventListener('DOMContentLoaded', function () {
        fetch('/Backend/logic/session_status.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then((response) => response.json())
            .then((data) => {
                const navbarIcons = document.getElementById('navbar-icons');
                navbarIcons.innerHTML = ''; // Clear existing icons

                // Always show search and home icons
                navbarIcons.innerHTML += `
                    <a href="/Frontend/sites/shop.php" class="nav-link text-dark p-2"><i class="fas fa-search"></i></a>
                    <a href="/index.php" class="nav-link text-dark p-2"><i class="fas fa-home"></i></a>
                `;

                if (data.loggedIn) {
                    // Show shopping bag and user icons for logged-in users
                    navbarIcons.innerHTML += `
                        <a href="/Frontend/sites/cart.php" class="nav-link text-dark p-2"><i class="fas fa-shopping-bag"> (<span id="cart-count">0</span>)</i></a>
                        <a href="/Frontend/sites/myprofile.php" class="nav-link text-dark p-2"><i class="fas fa-user"></i></a>
                        <a href="/Backend/logic/logout_process.php" class="btn btn-danger ms-3">Logout</a>
                    `;
                } else {
                    // Show login button for guests
                    navbarIcons.innerHTML += `
                        <a href="/Frontend/sites/login.php" class="btn btn-dark ms-3">Login</a>
                    `;
                }
            })
            .catch((error) => console.error('Error fetching session status:', error));
    });
</script>