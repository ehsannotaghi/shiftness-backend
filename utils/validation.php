<?php
// Validation helpers
// Place commonly used validation functions here and include with require_once

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidPassword($password) {
    return is_string($password) && strlen($password) >= 6;
}

?>