<?php
    require_once '../../Backend/config/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrierung</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../res/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../js/writeProfileDataAPI.js" defer></script>
  <script src="../js/script.js" defer></script>
</head>

<body>
  <?php include '../sites/navbar.php'; ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card mt-5">
          <div class="card-header text-center">
            <h3>Register</h3>
          </div>
          <div class="card-body">
            <form id="registration-form">
              <!-- Salutation -->
              <div class="mb-3">
                <label for="salutation" class="form-label">Anrede</label>
                <select class="form-select" id="salutation" name="salutation" required>
                  <option value="">Bitte auswählen...</option>
                  <option value="Mr.">Herr</option>
                  <option value="Ms.">Frau</option>
                  <option value="Other">Divers</option>
                </select>
              </div>

              <!-- First Name -->
              <div class="mb-3">
                <label for="firstName" class="form-label">Vorname</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
              </div>

              <!-- Last Name -->
              <div class="mb-3">
                <label for="lastName" class="form-label">Nachname</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
              </div>

              <!-- Address -->
              <div class="mb-3">
                <label for="address" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" required>
              </div>

              <!-- ZIP Code -->
              <div class="mb-3">
                <label for="zip" class="form-label">PLZ</label>
                <input type="text" class="form-control" id="zip" name="zip" required>
              </div>

              <!-- City -->
              <div class="mb-3">
                <label for="city" class="form-label">Stadt</label>
                <input type="text" class="form-control" id="city" name="city" required>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">E-Mail-Adresse</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>

              <!-- Username -->
              <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>

              <!-- Password -->
              <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>

              <!-- Confirm Password -->
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Passwort bestätigen</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
              </div>

              <!-- Submit Button -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Registrieren</button>
              </div>
            </form>
            <div id="registration-message" class="mt-3"></div>
          </div>
          <div class="card-footer text-center">
            Sie sind schon registriert? <a href="login.php">Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>