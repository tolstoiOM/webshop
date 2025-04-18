<?php
    require_once '../config/config.php';

    class AuthLogic
    {
        private $pdo;

        public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        public function registerUser($data)
        {
            // Validate required fields
            $requiredFields = ['salutation', 'firstName', 'lastName', 'address', 'zip', 'city', 'email', 'username', 'password'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return ['success' => false, 'message' => "Feld '$field' ist erforderlich."];
                }
            }

            // Validate salutation
            $validSalutations = ['Mr.', 'Ms.', 'Other'];
            if (!in_array($data['salutation'], $validSalutations)) {
                return ['success' => false, 'message' => 'Bitte wählen Sie eine gültige Anrede aus.'];
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.'];
            }

            // Validate password match
            if ($data['password'] !== $data['confirmPassword']) {
                return ['success' => false, 'message' => 'Die Passwörter stimmen nicht überein.'];
            }

            // Check if username or email already exists
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $data['username'], 'email' => $data['email']]);
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Benutzername oder E-Mail bereits vergeben.'];
            }

            // Hash the password
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            // Insert user into the database
            $stmt = $this->pdo->prepare("
                INSERT INTO users (salutation, first_name, last_name, address, zip, city, email, username, password)
                VALUES (:salutation, :firstName, :lastName, :address, :zip, :city, :email, :username, :password)
            ");

            $stmt->execute([
                'salutation' => $data['salutation'],
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'address' => $data['address'],
                'zip' => $data['zip'],
                'city' => $data['city'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $hashedPassword
            ]);

            return ['success' => true, 'message' => 'Registrierung erfolgreich.'];
        }


        public function loginUser($identifier, $password)
        {
            // Check if the user exists using email
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE (email = :identifier OR username = :identifier) AND status = 'active'");
            $stmt->execute(['identifier' => $identifier]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ['success' => false, 'message' => 'Ungültige E-Mail, Benutzername oder der Benutzer ist inaktiv.'];
            }

            // Verify the password
            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Ungültige E-Mail oder ungültiges Passwort.'];
            }

            // Start a session and store user information
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            return ['success' => true, 'message' => 'Login erfolgreich.'];
        }
    }
?>