<?php
// Database configuration
$host = "localhost";
$db_name = "db_app_user";
$username_db = "root";
$password_db = "";
$charset = "utf8mb4";

$dsn = "mysql:host={$host};dbname={$db_name};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

try {
    $conn = new PDO($dsn, $username_db, $password_db, $options);
} catch(PDOException $e) {
    // Log the error
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Show user-friendly message
    die("Maaf, terjadi kesalahan pada sistem. Silakan coba beberapa saat lagi.");
}

// Ensure the connection is configured for UTF-8
$conn->exec("SET NAMES utf8mb4");
?>