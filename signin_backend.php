<?php
require 'config.php'; // Include the database connection

$response = [
    'status' => 'error',
    'message' => 'An unknown error occurred.',
];

// Read and decode the JSON input sent via the fetch API
$requestBody = file_get_contents("php://input");
$data = json_decode($requestBody, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data)) {
    $firebaseUID = $data['firebaseUID']; // This should be retrieved after successful Firebase login

    try {
        // Fetch the user's data from the MySQL database
        $stmt = $conn->prepare("
            SELECT First_Name, Last_Name, Role, Email, Phone_Number, Gender, BirthDate
            FROM users
            WHERE User_ID = :firebaseUID
        ");

        $stmt->execute([':firebaseUID' => $firebaseUID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $response['status'] = 'success';
            $response['message'] = 'Sign-in successful!';
            $response['data'] = $user; // Return user information
        } else {
            $response['status'] = 'error';
            $response['message'] = 'User not found in the database.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
