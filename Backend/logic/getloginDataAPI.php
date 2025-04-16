<?php
    require_once '../config/config.php';
    require_once 'AuthLogic.php';

    header('Content-Type: application/json'); // Set the response type to JSON

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the raw POST data and decode the JSON
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $authLogic = new AuthLogic($pdo);

        // Get the email and password from the JSON data
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Call the loginUser method
        $result = $authLogic->loginUser($email, $password);

        // Return a JSON response
        echo json_encode($result);
        exit;
    }
?>