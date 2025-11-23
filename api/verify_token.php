<?php
require_once '../utils/cors.php';
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

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

try {
    // Find user by email
    $query = "SELECT id, email, password, created_at FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        sendResponse(401, false, "Invalid email or password");
        exit;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if (!password_verify($password, $user['password'])) {
        sendResponse(401, false, "Invalid email or password");
        exit;
    }

    // Generate JWT token
    $token = generateToken($user['id'], $user['email']);

    // Return success response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Sign in successful',
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
    sendResponse(500, false, "Sign in failed. Please try again later.");
}
?>