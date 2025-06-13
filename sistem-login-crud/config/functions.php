<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

// Validate password strength
function validate_password($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = "Password harus minimal 8 karakter.";
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password harus mengandung minimal 1 huruf kapital.";
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password harus mengandung minimal 1 huruf kecil.";
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password harus mengandung minimal 1 angka.";
    }
    
    return $errors;
}

// Clean user input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Set flash message
function set_flash_message($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Get flash message
function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// Check if user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>
