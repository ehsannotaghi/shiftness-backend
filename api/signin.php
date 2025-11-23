<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../config/database.php';
require_once '../utils/response.php';
require_once '../utils/validation.php';
require_once '../utils/auth.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(405, false, "Method not allowed");
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['email']) || !isset($data['password'])) {
    sendResponse(400, false, "Email and password are required");
    exit;
}

$email = trim($data['email']);
$password = $data['password'];

// Validate email format
if (!isValidEmail($email)) {
    sendResponse(400, false, "Invalid email format");
    exit;
}

// Validate password
if (strlen($password) < 6) {
    sendResponse(400, false, "Password must be at least 6 characters long");
    exit;
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

try {
    // Check if user already exists
    $checkQuery = "SELECT id FROM users WHERE email = :email";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        sendResponse(409, false, "Email already registered");
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $insertQuery = "INSERT INTO users (email, password, created_at) 
                    VALUES (:email, :password, NOW()) 
                    RETURNING id, email, created_at";
    $insertStmt = $db->prepare($insertQuery);
    $insertStmt->bindParam(':email', $email);
    $insertStmt->bindParam(':password', $hashedPassword);
    $insertStmt->execute();

    $user = $insertStmt->fetch(PDO::FETCH_ASSOC);

    // Generate JWT token
    $token = generateToken($user['id'], $email);

    // Return success response
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'User registered successfully',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'created_at' => $user['created_at']
        ]
    ]);
    exit;

} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(500, false, "Registration failed. Please try again later.");
}
?>