<!-- filepath: c:\xampp\htdocs\webshop\Frontend\sites\navbar.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand">
            <img src="../res/img/logo.png" alt="Harry Potter Shop" height="50" />
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Icons and Login/Logout Button -->
        <div class="d-flex align-items-center">
            <a href="/webshop/Frontend/sites/shop.php" class="nav-link text-dark p-2"><i class="fas fa-search"></i></a>
            <a href="/webshop/Frontend/sites/index.php" class="nav-link text-dark p-2"><i class="fas fa-home"></i></a>
            <a href="cart.php" class="nav-link text-dark p-2"><i class="fas fa-shopping-bag"> (<span
                        id="cart-count">0</span>)</i></a>
            <a href="myprofile.php" class="nav-link text-dark p-2"><i class="fas fa-user"></i></a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Logout Button -->
                <a href="/webshop/Backend/logic/logout_process.php" class="btn btn-danger ms-3">Logout</a>
            <?php else: ?>
                <!-- Login Button -->
                <a href="login.php" class="btn btn-dark ms-3">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>