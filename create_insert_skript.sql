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
	payment_method VARCHAR(50) DEFAULT NULL, -- Zahlungsmethode
	invoice_number VARCHAR(20) DEFAULT NULL, -- Rechnungsnummer
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
    expires_at DATETIME NOT NULL,
	cashed TINYINT(1) DEFAULT 0, -- 0 = nicht eingelöst, 1 = eingelöst
    residual_value DECIMAL(10, 2) DEFAULT NULL -- Restwert des Gutscheins
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
('Mr.', 'Albus', 'Dumbledore', 'Hogwarts School of Witchcraft and Wizardry', '00001', 'Hogwarts', 'albus.dumbledore@admin.com', 'admin', '$2y$10$NwWaLKhWbZa/H3pqMhaZrOiWs3VDyzzT5OV3PMY3SYYnfDC3iqk2m', 'administrator', 'active'),
('Mr.', 'Harry', 'Potter', 'Privet Drive 4', '12345', 'Little Whinging', 'harry.potter@example.com', 'harryp', '$2y$10$yeU09TuGSU7spSA11GWy2ePRK6vgjtHVoT/VeckHupUZeDl5SB6Sa', 'user', 'active'),
('Ms.', 'Hermione', 'Granger', 'The Burrow', '67890', 'Ottery St Catchpole', 'hermione.granger@example.com', 'hermione', '$2y$10$yeU09TuGSU7spSA11GWy2ePRK6vgjtHVoT/VeckHupUZeDl5SB6Sa', 'user', 'active'),
('Mr.', 'Ron', 'Weasley', 'Weasley House', '54321', 'Ottery St Catchpole', 'ron.weasley@example.com', 'ron', '$2y$10$yeU09TuGSU7spSA11GWy2ePRK6vgjtHVoT/VeckHupUZeDl5SB6Sa', 'user', 'active'),
('Ms.', 'Luna', 'Lovegood', 'Lovegood Cottage', '13579', 'Ottery St Catchpole', 'luna.lovegood@example.com', 'lunal', '$2y$10$yeU09TuGSU7spSA11GWy2ePRK6vgjtHVoT/VeckHupUZeDl5SB6Sa', 'user', 'active'),
('Mr.', 'Draco', 'Malfoy', 'Malfoy Manor', '24680', 'Wiltshire', 'draco.malfoy@example.com', 'dracom', '$2y$10$yeU09TuGSU7spSA11GWy2ePRK6vgjtHVoT/VeckHupUZeDl5SB6Sa', 'user', 'active'),
('Ms.', 'Minerva', 'McGonagall', 'Hogwarts Castle', '00002', 'Hogwarts', 'minerva.mcgonagall@example.com', 'mcgonagall', '$2y$10$yeU09TuGSU7spSA11GWy2ePRK6vgjtHVoT/VeckHupUZeDl5SB6Sa', 'user', 'active');

-- Beispiel-Produkte hinzufügen
INSERT INTO products (name, description, price, rating, image_path, category_id) VALUES
('Harry Potter und der Stein der Weisen', 'Das erste Buch der Harry Potter Reihe', 19.99, 'Ein zauberhaftes Buch – ein Muss für Fans!', '1.jpg', 1),
('Harry Potter Zauberstab', 'Originalgetreuer Zauberstab von Harry Potter', 29.99, 'Liegt super in der Hand – fast wie echte Magie!', '2.jpg', 2),
('Slytherin Hoodie', 'Hoodie mit dem Slytherin-Logo', 39.99, 'Sehr bequem und stylisch – perfekt für Fans!', '3.jpg', 3),
('Hermine Granger Funko Pop', 'Sammlerfigur von Hermine Granger', 15.99, 'Detailreich und niedlich – top Qualität!', '4.jpg', 4),
('Hogwarts Uhr', 'Wand-Uhr im Hogwarts-Stil', 49.99, 'Tolle Optik – bringt Hogwarts-Feeling nach Hause.', '5.jpg', 5),
('Dumbledore Ring', 'Ring von Albus Dumbledore', 99.99, 'Sehr edel – ein echtes Sammlerstück!', '6.jpg', 6),
('Hogwarts Geschenkbox', 'Geschenkbox mit verschiedenen Harry Potter Souvenirs', 59.99, 'Wunderschön verpackt – ideal als Geschenk!', '7.jpg', 7),
('Harry Potter Puzzle', '500-teiliges Puzzle mit Hogwarts-Motiv', 24.99, 'Spaß für die ganze Familie!', '1.jpg', 1),
('Marauders Karte', 'Originalgetreue Karte des Rumtreibers', 19.99, 'Sehr detailreich und magisch!', '2.jpg', 2),
('Ravenclaw Schal', 'Schal im Design von Ravenclaw', 14.99, 'Hält warm und sieht super aus!', '3.jpg', 3),
('Gryffindor Tasse', 'Keramiktasse mit Gryffindor-Wappen', 12.99, 'Toller Druck und spülmaschinenfest.', '4.jpg', 3),
('Hogwarts Schulrucksack', 'Robuster Rucksack mit Hogwarts-Logo', 44.99, 'Viel Platz und cooler Look!', '5.jpg', 2),
('Hedwig Stofftier', 'Plüschversion von Harrys Schneeeule Hedwig', 17.99, 'Superweich und detailgetreu!', '6.jpg', 4),
('Zaubertrank-Set', 'Deko-Set mit Mini-Zaubertrankflaschen', 21.99, 'Tolles Sammlerstück für Regale.', '7.jpg', 6),
('Harry Potter Poster-Set', '3 Poster mit ikonischen Szenen', 13.99, 'Schöne Drucke für echte Fans.', '1.jpg', 5),
('Nimbus 2000 Modell', 'Miniaturausgabe des berühmten Besens', 34.99, 'Ein Highlight für jedes Regal!', '2.jpg', 6),
('Hogwarts-Schlüsselanhänger', 'Metall-Schlüsselanhänger mit Logo', 6.99, 'Klein aber fein – macht Eindruck.', '3.jpg', 7),
('Harry Potter Sammelkarten', 'Booster-Pack mit 10 Karten', 9.99, 'Sammelspaß garantiert!', '4.jpg', 7),
('Professor McGonagall Funko Pop', 'Figur von Minerva McGonagall', 15.99, 'Würdige Ergänzung für jede Sammlung.', '5.jpg', 4),
('Harry Potter Umhang', 'Schwarzer Umhang mit Kapuze', 35.99, 'Fühlt sich echt magisch an!', '6.jpg', 2),
('Hufflepuff Mütze', 'Wärmende Mütze mit Hufflepuff-Emblem', 13.99, 'Weich, warm und super stylisch.', '7.jpg', 3);

-- Beispiel-Bestellung hinzufügen
INSERT INTO orders (user_id, total_price, status, payment_method)
VALUES 
(1, 45.99, 'pending', 2),
(2, 25.00, 'completed', 1),
(3, 59.49, 'completed', 3),
(4, 15.75, 'pending', 5),
(5, 89.90, 'completed', 4),
(6, 42.00, 'pending', 2),
(7, 37.80, 'pending', 1),
(1, 120.00, 'completed', 3),
(2, 63.25, 'pending', 2),
(3, 17.40, 'completed', 4),
(4, 32.99, 'completed', 5),
(5, 78.60, 'pending', 3),
(6, 12.50, 'pending', 1),
(7, 99.99, 'completed', 2),
(1, 54.30, 'pending', 5),
(2, 45.20, 'completed', 4),
(3, 67.85, 'completed', 3),
(4, 33.10, 'pending', 1),
(6, 71.99, 'completed', 2),
(7, 18.95, 'pending', 3);

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