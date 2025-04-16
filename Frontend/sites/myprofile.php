<?php
    require_once '../../Backend/config/session.php';
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mein Profil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../res/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/fetchProfileDataAPI.js" defer></script>
    <script src="../js/writeProfileDataAPI.js" defer></script>
    <script src="../js/script.js" defer></script>
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
                <p><strong>Anrede:</strong> <span id="salutation"></span></p>
                <p><strong>Vorname:</strong> <span id="first_name"></span></p>
                <p><strong>Nachname:</strong> <span id="last_name"></span></p>
                <p>
                    <strong>Adresse:</strong>
                    <span id="address"></span>
                    <button type="button" class="btn btn-link toggle-visibility" data-target="address">Anzeigen</button>
                </p>
                <p>
                    <strong>PLZ:</strong>
                    <span id="zip"></span>
                    <button type="button" class="btn btn-link toggle-visibility" data-target="zip">Anzeigen</button>
                </p>
                <p>
                    <strong>Stadt:</strong>
                    <span id="city"></span>
                    <button type="button" class="btn btn-link toggle-visibility" data-target="city">Anzeigen</button>
                </p>
                <p>
                    <strong>E-Mail:</strong>
                    <span id="email"></span>
                    <button type="button" class="btn btn-link toggle-visibility" data-target="email">Anzeigen</button>
                </p>
                <p><strong>Benutzername:</strong> <span id="username"></span></p>

                <div class="card-footer text-center">
                    <a class="btn btn-primary" href="/Frontend/sites/myorders.php" class="btn btn-primary mt-3">Meine Bestellungen</a>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Daten bearbeiten</button>
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
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                            <button type="button" id="saveChanges" class="btn btn-primary">Speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>