<?php
class Product
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllProducts()
    {
        $stmt = $this->pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Bildpfad anpassen
        foreach ($products as &$product) {
            if (!empty($product['image_path'])) {
                $product['image_path'] = '/Backend/productpictures/' . $product['image_path'];
            }
    }

    return $products;
    }

    public function createProduct($data)
    {
        // Validierung der Eingabedaten
        if (
            empty($data['name']) ||
            empty($data['description']) ||
            empty($data['price']) ||
            !isset($data['rating']) ||
            empty($data['category_id'])
        ) {
            return ['success' => false, 'message' => 'Alle erforderlichen Felder müssen ausgefüllt werden.'];
        }

        // Bildpfad validieren
        $image_path = isset($data['image_path']) ? basename($data['image_path']) : null;

        // SQL-Abfrage ausführen
        $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price, rating, image_path, category_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['rating'],
            $image_path,
            $data['category_id']
        ]);
        return ['success' => true, 'message' => 'Produkt erfolgreich hinzugefügt.'];
    }

    public function updateProduct($data)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, rating = ?, image_path = ?, category_id = ? WHERE id = ?");
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['rating'],
            $data['image_path'],
            $data['category_id'],
            $data['id']
        ]);
        return ['success' => true, 'message' => 'Produkt erfolgreich aktualisiert.'];
    }

    public function deleteProduct($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return ['success' => true, 'message' => 'Produkt erfolgreich gelöscht.'];
    }
}
?>