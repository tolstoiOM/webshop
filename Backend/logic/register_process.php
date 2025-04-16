<?php
    require_once '../../Backend/config/session.php';
    require_once '../config/config.php';
    require_once 'AuthLogic.php';
    header('Content-Type: application/json'); // Set the response type to JSON

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Validate passwords match
        if ($data['password'] !== $data['confirmPassword']) {
            echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
            exit;
        }

        $authLogic = new AuthLogic($pdo);

        // Call the registerUser method
        $result = $authLogic->registerUser($data);

        // Return a JSON response
        echo json_encode($result);
        exit;
    }
?>