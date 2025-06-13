<?php
require_once 'auth_check.php';
require_once '../config/database.php';
require_once '../config/functions.php';

// Verify CSRF token if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['message'] = "Invalid security token.";
        $_SESSION['message_type'] = "error";
        header("Location: index.php");
        exit();
    }
}

$user_id_to_delete = $_GET['id'] ?? null;

// Validate user ID
if (!$user_id_to_delete || !filter_var($user_id_to_delete, FILTER_VALIDATE_INT)) {
    $_SESSION['message'] = "ID User tidak valid untuk dihapus.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}

// Prevent self-deletion
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id_to_delete) {
    $_SESSION['message'] = "Anda tidak dapat menghapus akun Anda sendiri.";
    $_SESSION['message_type'] = "warning";
    header("Location: index.php");
    exit();
}

try {
    // Check if user exists and get their role
    $stmt_check = $conn->prepare("SELECT role FROM users WHERE id = :id");
    $stmt_check->bindParam(':id', $user_id_to_delete, PDO::PARAM_INT);
    $stmt_check->execute();
    $user_to_delete = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$user_to_delete) {
        $_SESSION['message'] = "User tidak ditemukan.";
        $_SESSION['message_type'] = "error";
        header("Location: index.php");
        exit();
    }

    // Check if trying to delete the last admin
    if ($user_to_delete['role'] === 'admin') {
        $stmt_count_admin = $conn->prepare("SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'");
        $stmt_count_admin->execute();
        $admin_count = $stmt_count_admin->fetch(PDO::FETCH_ASSOC)['admin_count'];

        if ($admin_count <= 1) {
            $_SESSION['message'] = "Tidak dapat menghapus admin terakhir.";
            $_SESSION['message_type'] = "error";
            header("Location: index.php");
            exit();
        }
    }

    // If all checks pass, delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id_to_delete, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "User berhasil dihapus!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "User tidak ditemukan atau sudah dihapus.";
            $_SESSION['message_type'] = "warning";
        }
    } else {
        $_SESSION['message'] = "Gagal menghapus user.";
        $_SESSION['message_type'] = "error";
    }

} catch (PDOException $e) {
    error_log("Error deleting user: " . $e->getMessage());
    $_SESSION['message'] = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
    $_SESSION['message_type'] = "error";
}

header("Location: index.php");
exit();
?>