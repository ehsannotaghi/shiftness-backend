<?php
// Standardized response helper
// Define sendResponse if not already defined elsewhere
if (!function_exists('sendResponse')) {
	function sendResponse($statusCode, $success, $message, $data = null, $error = null) {
		http_response_code($statusCode);
		header('Content-Type: application/json; charset=utf-8');
		$response = [
			'success' => $success,
			'message' => $message
		];
		if ($data !== null) {
			$response['data'] = $data;
		}
		if ($error !== null) {
			$response['error'] = $error;
		}
		echo json_encode($response);
		exit;
	}
}
?>