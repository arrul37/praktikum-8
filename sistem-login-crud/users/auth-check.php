<?php
// users/auth_check.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if session has expired
if (isset($_SESSION['expires']) && $_SESSION['expires'] < time()) {
    session_destroy();
    session_start();
    $_SESSION['error_message'] = "Sesi Anda telah berakhir. Silakan login kembali.";
    header("Location: ../auth/login.php");
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Anda harus login untuk mengakses halaman ini.";
    header("Location: ../auth/login.php");
    exit();
}

// Renew session expiry time
$_SESSION['expires'] = time() + (2 * 60 * 60); // 2 hours

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to require admin role
function requireAdmin() {
    if (!isAdmin()) {
        $_SESSION['error_message'] = "Anda tidak memiliki hak akses admin.";
        header("Location: index.php");
        exit();
    }
}

// Prevent session fixation
if (!isset($_SESSION['last_regeneration']) || time() - $_SESSION['last_regeneration'] >= 30 * 60) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
?>
