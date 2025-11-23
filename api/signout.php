<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../config/database.php';
require_once '../utils/response.php';
require_once '../utils/auth.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(405, false, "Method not allowed");
    exit;
}

// Get token from Authorization header
$headers = getallheaders();
$token = null;

if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        $token = $matches[1];
    }
}

if (!$token) {
    sendResponse(401, false, "Token not provided");
    exit;
}

// Verify token
$decoded = verifyToken($token);

if (!$decoded) {
    sendResponse(401, false, "Invalid or expired token");
    exit;
}

// Get user from database
$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT id, email, created_at FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $decoded['user_id']);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        sendResponse(401, false, "User not found");
        exit;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Token is valid',
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'created_at' => $user['created_at']
        ]
    ]);
    exit;

} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(500, false, "Verification failed");
}
?>