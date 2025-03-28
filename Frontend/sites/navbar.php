<!-- filepath: c:\xampp\htdocs\webshop\Frontend\sites\navbar.php -->
<?php
session_start(); // Start the session to access session variables
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="../sites/index.php">
            <img src="../res/img/logo.png" alt="Harry Potter Shop" height="50" />
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Houses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Gifts</a>
                </li>
            </ul>
        </div>

        <!-- Icons and Login/Logout Button -->
        <div class="d-flex align-items-center">
            <a href="#" class="nav-link text-dark p-2"><i class="fas fa-search"></i></a>
            <a href="#" class="nav-link text-dark p-2"><i class="fas fa-heart"></i></a>
            <a href="cart.php" class="nav-link text-dark p-2"><i class="fas fa-shopping-bag"></i></a>
            <a href="#" class="nav-link text-dark p-2"><i class="fas fa-user"></i></a>

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