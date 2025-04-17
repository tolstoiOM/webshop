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
                    return ['success' => false, 'message' => "Field '$field' is required."];
                }
            }

            // Check if username or email already exists
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $data['username'], 'email' => $data['email']]);
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Username or email already exists.'];
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

            return ['success' => true, 'message' => 'Registration successful.'];
        }


        public function loginUser($email, $password)
        {
            // Check if the user exists using email
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ['success' => false, 'message' => 'Invalid email or password.'];
            }

            // Verify the password
            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Invalid email or password.'];
            }

            // Start a session and store user information
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // Optional: Store username if needed
            $_SESSION['email'] = $user['email'];

            return ['success' => true, 'message' => 'Login successful.'];
        }
    }
?>