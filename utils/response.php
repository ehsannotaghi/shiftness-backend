<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../utils/response.php';

// Sign out is handled on the client side by removing the token
// This endpoint is just for consistency
sendResponse(200, true, "Signed out successfully");
?>