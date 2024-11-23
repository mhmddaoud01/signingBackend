<?php
require 'config.php'; // Include database connection

$response = [
    'status' => 'error',
    'message' => 'An unknown error occurred.',
];

// Handle the POST request
$requestBody = file_get_contents("php://input");
$data = json_decode($requestBody, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data)) {
    $firebaseUID = $data['firebaseUID'];
    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $email = $data['email'];
    $phone = $data['mobile'];
    $gender = $data['gender'];
    $birthDate = $data['dob'];
    $role = $data['role'] ?? 0;

    try {
        $stmt = $conn->prepare("
            INSERT INTO users (User_ID, Role, First_Name, Last_Name, Email, Phone_Number, Gender, BirthDate)
            VALUES (:firebaseUID, :role, :first_name, :last_name, :email, :phone, :gender, :birthDate)
        ");
        $stmt->execute([
            ':firebaseUID' => $firebaseUID,
            ':role' => $role,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':phone' => $phone,
            ':gender' => $gender,
            ':birthDate' => $birthDate,
        ]);

        $response['status'] = 'success';
        $response['message'] = 'User registered successfully!';
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
