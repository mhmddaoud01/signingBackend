<?php
session_start();

$host = '127.0.0.1';
$db = 'mybeirut';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]));
}

// Function to check if user is logged in
function checkLogin() {
    if (!isset($_SESSION['firebaseUID'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit;
    }
}
?>
