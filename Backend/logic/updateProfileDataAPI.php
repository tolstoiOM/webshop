<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    header('Content-Type: application/json'); // JSON-Antwort

    // JSON-Daten aus der Anfrage lesen
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Debugging: Überprüfen Sie die empfangenen Daten
    error_log('Empfangene JSON-Daten: ' . print_r($data, true));

    // Überprüfen Sie, ob alle erforderlichen Daten vorhanden sind
    if (!isset($data['field'], $data['newValue'], $data['password'], $data['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage.']);
        exit();
    }

    $field = $data['field'];
    $newValue = $data['newValue'];
    $password = $data['password'];
    $user_id = $data['user_id'];

    // Debugging: Überprüfen Sie die extrahierten Daten
    error_log('Extrahierte Daten: ' . print_r(compact('field', 'newValue', 'password', 'user_id'), true));

    // Sicherheitsmaßnahmen: Nur erlaubte Felder aktualisieren
    $allowed_fields = ['salutation', 'first_name', 'last_name', 'address', 'zip', 'city', 'email', 'username'];
    if (!in_array($field, $allowed_fields)) {
        echo json_encode(['success' => false, 'message' => 'Ungültiges Feld: ' . htmlspecialchars($field)]);
        exit();
    }

    // Passwort überprüfen
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Falsches Passwort.']);
        exit();
    }

    // SQL-Abfrage vorbereiten
    $sql = "UPDATE users SET $field = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("si", $newValue, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profil erfolgreich aktualisiert.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Fehler beim Aktualisieren der Daten: ' . $stmt->error]);
    }
    exit();
?>