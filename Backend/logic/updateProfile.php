<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../config/config.php';

if (!isset($_POST['field'], $_POST['newValue'], $_POST['user_id'])) {
    die("Ungültige Anfrage.");
}

$field = $_POST['field'];
$newValue = $_POST['newValue'];
$user_id = $_POST['user_id'];

// Debugging: POST-Daten prüfen
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Sicherheitsmaßnahmen: Nur erlaubte Felder aktualisieren
$allowed_fields = ['salutation', 'first_name', 'last_name', 'address', 'zip', 'city', 'email', 'username'];
if (!in_array($field, $allowed_fields)) {
    die("Ungültiges Feld: " . htmlspecialchars($field));
}

// SQL-Abfrage vorbereiten
$sql = "UPDATE users SET $field = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Debugging: SQL-Abfrage prüfen
echo "SQL: UPDATE users SET $field = ? WHERE id = ?";
$stmt->bind_param("si", $newValue, $user_id);

if ($stmt->execute()) {
    // Erfolgreich aktualisiert, zurück zur Profilseite mit Erfolgsmeldung
    header("Location: ../../Frontend/sites/myprofile.php?success=1");
    exit();
} else {
    // Debugging: Fehler ausgeben
    echo "Fehler beim Aktualisieren der Daten: " . $stmt->error;
    exit();
}
?>