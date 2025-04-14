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

    function maskSensitiveData($data) {
        return '****';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Harry Potter Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js"></script>
</head>

<body>
    <?php include '../sites/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Mein Konto</h2>
        <div class="card mt-4">
            <div class="card-header">
                <h4>Persönliche Daten</h4>
            </div>
            <div class="card-body">
    <p>
        <strong>Anrede:</strong> <?php echo htmlspecialchars($user['salutation']); ?>
    </p>
    <p>
        <strong>Vorname:</strong> <?php echo htmlspecialchars($user['first_name']); ?>
    </p>
    <p>
        <strong>Nachname:</strong> <?php echo htmlspecialchars($user['last_name']); ?>
    </p>
    <p>
        <strong>Adresse:</strong>
        <span id="address-value"><?php echo maskSensitiveData($user['address']); ?></span>
        <button type="button" class="btn btn-link toggle-visibility" data-target="address-value" data-original="<?php echo htmlspecialchars($user['address']); ?>">Anzeigen</button>
    </p>
    <p>
        <strong>PLZ:</strong>
        <span id="zip-value"><?php echo maskSensitiveData($user['zip']); ?></span>
        <button type="button" class="btn btn-link toggle-visibility" data-target="zip-value" data-original="<?php echo htmlspecialchars($user['zip']); ?>">Anzeigen</button>
    </p>
    <p>
        <strong>Stadt:</strong>
        <span id="city-value"><?php echo maskSensitiveData($user['city']); ?></span>
        <button type="button" class="btn btn-link toggle-visibility" data-target="city-value" data-original="<?php echo htmlspecialchars($user['city']); ?>">Anzeigen</button>
    </p>
    <p>
        <strong>E-Mail:</strong>
        <span id="email-value"><?php echo maskSensitiveData($user['email']); ?></span>
        <button type="button" class="btn btn-link toggle-visibility" data-target="email-value" data-original="<?php echo htmlspecialchars($user['email']); ?>">Anzeigen</button>
    </p>
    <p>
        <strong>Benutzername:</strong> <?php echo htmlspecialchars($user['username']); ?>
    </p>
    <div class="card-footer text-center">
    <a class="btn btn-primary" href="/Frontend/sites/myorders.php" class="btn btn-primary mt-3">Meine Bestellungen</a>
    </button>
    </div>
</div>
            <div class="card-footer text-center">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                    Daten bearbeiten
                </button>
            </div>
        </div>
    </div>

    <!-- Bearbeitungsmodal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Stammdaten bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div id="error-message" class="text-danger mb-3" style="display: none;"></div> <!-- Fehlerbereich -->
                        <div class="mb-3">
                            <label for="field" class="form-label">Feld auswählen</label>
                            <select class="form-select" id="field" name="field" required>
                                <option value="first_name">Vorname</option>
                                <option value="last_name">Nachname</option>
                                <option value="address">Adresse</option>
                                <option value="zip">PLZ</option>
                                <option value="city">Stadt</option>
                                <option value="email">E-Mail</option>
                                <option value="username">Benutzername</option>
                                <option value="salutation">Anrede</option>
                            </select>
                        </div>
                        <div class="mb-3" id="newValueContainer">
                            <label for="newValue" class="form-label">Neuer Wert</label>
                            <input type="text" class="form-control" id="newValue" name="newValue" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Passwort</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                            <button type="button" id="saveChanges" class="btn btn-primary">Speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#saveChanges').click(function() {
            console.log('Speichern-Button wurde geklickt'); // Debugging-Ausgabe

            const field = $('#field').val();
            const newValue = $('#newValue').val();
            const userId = $('#user_id').val();
            const password = $('#password').val();
            const errorMessage = $('#error-message');

            // Fehlerbereich zurücksetzen
            errorMessage.hide().text('');

            if (!password.trim()) {
                errorMessage.text('Bitte geben Sie Ihr Passwort ein.').show();
                return;
            }

            console.log('Daten, die gesendet werden:', { field, newValue, userId, password }); // Debugging-Ausgabe

            $.ajax({
                url: '/Backend/logic/updateProfile.php',
                method: 'POST',
                data: {
                    field: field,
                    newValue: newValue,
                    password: password,
                    user_id: userId
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Antwort vom Server:', response); // Debugging-Ausgabe
                    if (response.success) {
                        alert(response.message);
                        location.reload(); // Seite neu laden, um die Änderungen anzuzeigen
                    } else {
                        errorMessage.text(response.message).show(); // Fehler anzeigen
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Fehler bei der AJAX-Anfrage:', error); // Debugging-Ausgabe
                    errorMessage.text('Fehler beim Aktualisieren der Daten.').show();
                }
            });
        });
   

    $('.toggle-visibility').click(function() {
            const targetId = $(this).data('target');
            const originalValue = $(this).data('original');
            const targetElement = $('#' + targetId);

            if ($(this).text() === 'Anzeigen') {
                // Zeige den Originalwert an
                targetElement.text(originalValue);
                $(this).text('Verbergen');
            } else {
                // Maskiere den Wert
                targetElement.text('*'.repeat(originalValue.length));
                $(this).text('Anzeigen');
            }
        });
    });
</script>
</body>

</html>