<?php
// Email validation
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Password validation
function isValidPassword($password) {
    return strlen($password) >= 6;
}
?>