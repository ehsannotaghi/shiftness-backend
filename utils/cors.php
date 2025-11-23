<?php
// CORS helper: set headers for API responses and handle preflight requests
// Include this file at the top of API entrypoints (from files in `api/` use require_once '../utils/cors.php')

// Allow requests from any origin (change to specific origin for production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With");
// If your frontend sends cookies or uses credentials, change the origin and set this to true
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");

// If this is a preflight request, return 200 immediately
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    // no body for OPTIONS
    exit;
}

?>
