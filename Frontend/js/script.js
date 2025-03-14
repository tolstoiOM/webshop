document.addEventListener("DOMContentLoaded", function() {
    fetchProducts();
});

function fetchProducts() {
    fetch("backend/get_products.php")
        .then(response => response.json())
        .then(data => {
            let container = document.getElementById("product-container");
            container.innerHTML = "";

            data.forEach(product => {
                let productCard = `
                    <div class="col-md-3">
                        <div class="card">
                            <img src="uploads/${product.image}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.price} €</p>
                                <button class="btn btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += productCard;
            });
        });
}

function addToCart(productId) {
    fetch("backend/add_to_cart.php?id=" + productId)
        .then(response => response.text())
        .then(data => {
            document.getElementById("cart-count").innerText = data;
        });
}
