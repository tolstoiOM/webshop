<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verbindung zur Datenbank einbinden
require_once '../../Backend/config/config.php';

// Prüfen, ob die Verbindung existiert
if (!isset($conn)) {
    die("Database connection not established.");
}

$user_id = $_SESSION['user_id'];

// Benutzerdaten aus der Datenbank abrufen
$sql = "SELECT salutation, first_name, last_name, address, zip, city, email, username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Benutzerdaten konnten nicht geladen werden.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Harry Potter Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include '../sites/navbar.php'; ?>

    <!-- Erfolgsmeldung -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center" role="alert">
            Ihre Daten wurden erfolgreich aktualisiert.
        </div>
    <?php endif; ?>

    <!-- Fehlermeldung -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="alert alert-danger text-center" role="alert">
            Es ist ein Fehler beim Speichern Ihrer Daten aufgetreten. Bitte versuchen Sie es erneut.
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <h2 class="text-center">My Profile</h2>
        <div class="card mt-4">
            <div class="card-header">
                <h4>Personal Information</h4>
            </div>
            <div class="card-body">
                <p><strong>Salutation:</strong> <?php echo htmlspecialchars($user['salutation']); ?></p>
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <p><strong>ZIP Code:</strong> <?php echo htmlspecialchars($user['zip']); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            </div>
            <!-- Button zum Öffnen des Modals -->
            <div class="card-footer text-center">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                    Daten bearbeiten
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Stammdaten bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="/webshop/Backend/logic/updateProfile.php" method="POST">
                        <div class="mb-3">
                            <label for="field" class="form-label">Feld auswählen</label>
                            <select class="form-select" id="field" name="field" required>
                                <option value="salutation">Salutation</option>
                                <option value="first_name">First Name</option>
                                <option value="last_name">Last Name</option>
                                <option value="address">Address</option>
                                <option value="zip">ZIP Code</option>
                                <option value="city">City</option>
                                <option value="email">Email</option>
                                <option value="username">Username</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="newValue" class="form-label">Neuer Wert</label>
                            <input type="text" class="form-control" id="newValue" name="newValue" required>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                            <button type="submit" class="btn btn-primary">Speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>