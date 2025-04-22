-- Datenbank erstellen
CREATE DATABASE IF NOT EXISTS webshop;

-- Zur erstellten Datenbank wechseln
USE webshop;

-- Tabelle 'users' erstellen
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    salutation VARCHAR(10) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address VARCHAR(255) NOT NULL,
    zip VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('administrator', 'user') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle 'categories' erstellen
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Tabelle 'products' erstellen
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    rating VARCHAR(255) DEFAULT NULL,
    image_path VARCHAR(255),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Tabelle 'orders' erstellen
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'canceled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabelle 'order_items' erstellen
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tabelle 'cart' erstellen
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tabelle 'coupons' erstellen
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(5) NOT NULL UNIQUE,
    value DECIMAL(10, 2) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NOT NULL
);

-- Tabelle 'payment_methods' erstellen
CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    method ENUM('Kreditkarte', 'Paypal', 'Klarna', 'Kauf auf Rechnung', 'Apple Pay') NOT NULL,
    card_number VARCHAR(16), -- Für Kreditkarte
    expiry_date VARCHAR(5), -- MM/YY für Kreditkarte
    cvv VARCHAR(3), -- Für Kreditkarte
    paypal_email VARCHAR(255), -- Für Paypal
    klarna_account VARCHAR(255), -- Für Klarna
    billing_address VARCHAR(255), -- Für Kauf auf Rechnung
    apple_pay_token VARCHAR(255), -- Für Apple Pay
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Kategorien einfügen
INSERT INTO categories (name) VALUES
('Bücher'),
('Kleidung'),
('Accessoires'),
('Spielzeug'),
('Dekoration'),
('Schmuck'),
('Geschenkideen');

-- Beispiel-Nutzer hinzufügen
INSERT INTO users (salutation, first_name, last_name, address, zip, city, email, username, password, role, status)
VALUES 
('Mr.', 'Albus', 'Dumbledore', 'Hogwarts School of Witchcraft and Wizardry', '00001', 'Hogwarts', 'albus.dumbledore@admin.com', 'admin', '$2y$10$CcvDI455H./eHv4peVrLJe4eEUwr8NTD16Cx49BG8qXUup1m3034u', 'administrator', 'active'),
('Mr.', 'Harry', 'Potter', 'Privet Drive 4', '12345', 'Little Whinging', 'harry.potter@example.com', 'harryp', '$2y$10$CcvDI455H./eHv4peVrLJe4eEUwr8NTD16Cx49BG8qXUup1m3034u', 'user', 'active'),
('Ms.', 'Hermione', 'Granger', 'The Burrow', '67890', 'Ottery St Catchpole', 'hermione.granger@example.com', 'hermione', '$2y$10$CcvDI455H./eHv4peVrLJe4eEUwr8NTD16Cx49BG8qXUup1m3034u', 'user', 'active'),
('Mr.', 'Ron', 'Weasley', 'Weasley House', '54321', 'Ottery St Catchpole', 'ron.weasley@example.com', 'ron', '$2y$10$CcvDI455H./eHv4peVrLJe4eEUwr8NTD16Cx49BG8qXUup1m3034u', 'user', 'active');

-- Beispiel-Produkte hinzufügen
INSERT INTO products (name, description, price, rating, image_path, category_id) VALUES
('Harry Potter und der Stein der Weisen', 'Das erste Buch der Harry Potter Reihe', 19.99, 'Ein zauberhaftes Buch – ein Muss für Fans!', '1.jpg', 1),
('Harry Potter Zauberstab', 'Originalgetreuer Zauberstab von Harry Potter', 29.99, 'Liegt super in der Hand – fast wie echte Magie!', '2.jpg', 2),
('Slytherin Hoodie', 'Hoodie mit dem Slytherin-Logo', 39.99, 'Sehr bequem und stylisch – perfekt für Fans!', '3.jpg', 3),
('Hermine Granger Funko Pop', 'Sammlerfigur von Hermine Granger', 15.99, 'Detailreich und niedlich – top Qualität!', '4.jpg', 4),
('Hogwarts Uhr', 'Wand-Uhr im Hogwarts-Stil', 49.99, 'Tolle Optik – bringt Hogwarts-Feeling nach Hause.', '5.jpg', 5),
('Dumbledore Ring', 'Ring von Albus Dumbledore', 99.99, 'Sehr edel – ein echtes Sammlerstück!', '6.jpg', 6),
('Hogwarts Geschenkbox', 'Geschenkbox mit verschiedenen Harry Potter Souvenirs', 59.99, 'Wunderschön verpackt – ideal als Geschenk!', '7.jpg', 7);

-- Beispiel-Bestellung hinzufügen
INSERT INTO orders (user_id, total_price, status)
VALUES 
(1, 45.99, 'pending'),
(2, 25.00, 'completed');

-- Beispiel-Gutscheine einfügen
INSERT INTO coupons (code, value, created_at, expires_at) VALUES
('A1B2C', 10.00, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY)),
('D3E4F', 15.00, NOW(), DATE_ADD(NOW(), INTERVAL 60 DAY)),
('G5H6I', 20.00, NOW(), DATE_ADD(NOW(), INTERVAL 90 DAY)),
('J7K8L', 25.00, NOW(), DATE_ADD(NOW(), INTERVAL 120 DAY)),
('M9N0O', 30.00, NOW(), DATE_ADD(NOW(), INTERVAL 150 DAY)),
('P1Q2R', 35.00, NOW(), DATE_ADD(NOW(), INTERVAL 180 DAY)),
('S3T4U', 40.00, NOW(), DATE_ADD(NOW(), INTERVAL 210 DAY)),
('V5W6X', 45.00, NOW(), DATE_ADD(NOW(), INTERVAL 240 DAY)),
('Y7Z8A', 50.00, NOW(), DATE_ADD(NOW(), INTERVAL 270 DAY)),
('B9C0D', 55.00, NOW(), DATE_ADD(NOW(), INTERVAL 300 DAY));

-- Rechte für einen Benutzer erstellen
CREATE USER 'webprojectuser'@'localhost' IDENTIFIED BY 'xSnsN)F3!wg[vbPk';

GRANT ALL PRIVILEGES ON webshop.* TO 'webprojectuser'@'localhost';

FLUSH PRIVILEGES;