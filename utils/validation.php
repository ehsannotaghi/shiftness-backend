<?php
// Validation helpers only. Do NOT redeclare sendResponse here.

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidPassword($password) {
    return is_string($password) && strlen($password) >= 6;
}

?>