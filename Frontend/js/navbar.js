document.addEventListener('DOMContentLoaded', function () {
    fetch('/Backend/logic/getSessionStatus.php', {
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

            if (data.loggedIn && data.role === 'administrator') {
                navbarIcons.innerHTML += `
                    <a href="/Frontend/sites/cart.php" class="nav-link text-dark p-2" id="cart-icon" ondragover="handleDragOver(event)" ondrop="handleDrop(event)"><i class="fas fa-shopping-bag"> (<span id="cart-count">0</span>)</i></a>
                    <a href="/Frontend/sites/myprofile.php" class="nav-link text-dark p-2"><i class="fas fa-user"></i></a>
                    <a href="/Frontend/sites/users.php" class="nav-link text-dark p-2"><i class="fas fa-users"></i></a>
                    <a href="/Backend/logic/logoutLogic.php" class="btn btn-danger ms-3">Logout</a>
                `;
            } else if (data.loggedIn) {
                // Show shopping bag and user icons for logged-in users
                navbarIcons.innerHTML += `
                    <a href="/Frontend/sites/cart.php" class="nav-link text-dark p-2" id="cart-icon" ondragover="handleDragOver(event)" ondrop="handleDrop(event)"><i class="fas fa-shopping-bag"> (<span id="cart-count">0</span>)</i></a>
                    <a href="/Frontend/sites/myprofile.php" class="nav-link text-dark p-2"><i class="fas fa-user"></i></a>
                    <a href="/Backend/logic/logoutLogic.php" class="btn btn-danger ms-3">Logout</a>
                `;
            } else {
                // Show shopping bag and login button for guests
                navbarIcons.innerHTML += `
                    <a href="/Frontend/sites/cart.php" class="nav-link text-dark p-2" id="cart-icon"><i class="fas fa-shopping-bag"> (<span id="cart-count">0</span>)</i></a>
                    <a href="/Frontend/sites/login.php" class="btn btn-dark ms-3">Login</a>
                `;
            }
            // Update the cart count for both logged-in and not logged-in users
            updateCartCount();
        })
        .catch((error) => console.error('Error fetching session status:', error));
});

// Funktion zum Aktualisieren der Warenkorb-Anzahl
function updateCartCount() {
    fetch('/Backend/logic/getProductsAPI.php?action=getCartCount', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.cartCount;
                }
            } else {
                console.error('Fehler beim Abrufen der Warenkorb-Anzahl:', data.message);
            }
        })
        .catch((error) => console.error('Fehler beim Abrufen der Warenkorb-Anzahl:', error));
}