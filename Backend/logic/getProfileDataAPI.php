<?php
    require_once '../../Backend/config/session.php';
    header('Content-Type: application/json');

    // Überprüfen, ob der Benutzer eingeloggt ist
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Benutzer nicht eingeloggt.']);
        exit;
    }

    require_once '../config/config.php';

    $user_id = $_SESSION['user_id'];

    // Benutzerdaten aus der Datenbank abrufen
    $sql = "SELECT salutation, first_name, last_name, address, zip, city, email, username FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Datenbankfehler: ' . $pdo->errorInfo()]);
        exit;
    }
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['error' => 'Benutzerdaten konnten nicht geladen werden.']);
        exit;
    }

    // JSON-Antwort zurückgeben
    echo json_encode(['success' => true, 'user' => $user]);
?>