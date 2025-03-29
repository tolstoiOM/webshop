-- Datenbank erstellen
CREATE DATABASE webshop;

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
    rating DECIMAL(2, 1) NOT NULL,
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
('Harry Potter und der Stein der Weisen', 'Das erste Buch der Harry Potter Reihe', 19.99, 4.8, '../res/img/1.jpg', 1),
('Harry Potter Zauberstab', 'Originalgetreuer Zauberstab von Harry Potter', 29.99, 4.7, '../res/img/2.jpg', 2),
('Slytherin Hoodie', 'Hoodie mit dem Slytherin-Logo', 39.99, 4.6, '../res/img/3.jpg', 3),
('Hermine Granger Funko Pop', 'Sammlerfigur von Hermine Granger', 15.99, 4.9, '../res/img/4.jpg', 4),
('Hogwarts Uhr', 'Wand-Uhr im Hogwarts-Stil', 49.99, 4.5, '../res/img/5.jpg', 5),
('Dumbledore Ring', 'Ring von Albus Dumbledore', 99.99, 4.8, '../res/img/6.jpg', 6),
('Hogwarts Geschenkbox', 'Geschenkbox mit verschiedenen Harry Potter Souvenirs', 59.99, 4.7, '../res/img/7.jpg', 7);


-- Beispiel-Bestellung hinzufügen
INSERT INTO orders (user_id, total_price, status)
VALUES 
(1, 45.99, 'pending'),
(2, 25.00, 'completed');

-- Rechte für einen Benutzer erstellen
CREATE USER 'webprojectuser'@'localhost' IDENTIFIED BY 'xSnsN)F3!wg[vbPk';

GRANT ALL PRIVILEGES ON webshop.* TO 'webprojectuser'@'localhost';

FLUSH PRIVILEGES;
