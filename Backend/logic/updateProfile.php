<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    require_once '../config/config.php';

    header('Content-Type: application/json'); // JSON-Antwort

    // Debugging: Überprüfen Sie, ob die Datei korrekt aufgerufen wird
    error_log('updateProfile.php wurde aufgerufen');

    // Überprüfen Sie, ob alle erforderlichen POST-Daten vorhanden sind
    if (!isset($_POST['field'], $_POST['newValue'], $_POST['password'], $_POST['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage.']);
        exit();
    }

    $field = $_POST['field'];
    $newValue = $_POST['newValue'];
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];

    // Debugging: POST-Daten prüfen
    error_log('POST-Daten: ' . print_r($_POST, true));

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