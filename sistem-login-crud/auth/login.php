<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';

// Regenerate session ID for security
if (!empty($_POST)) {
    session_regenerate_id(true);
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../users/index.php");
    exit();
}

$errors = [];
$username = "";

// Handle flash messages
$flash_message = get_flash_message();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid form submission, please try again.";
    } else {
        $username = clean_input($_POST['username']);
        $password = $_POST['password'];

        if (empty($username)) {
            $errors[] = "Username wajib diisi.";
        }
        if (empty($password)) {
            $errors[] = "Password wajib diisi.";
        }

        if (empty($errors)) {
            try {
                $stmt = $conn->prepare("SELECT id, username, password, nama_lengkap, role, last_login 
                                      FROM users WHERE username = :username LIMIT 1");
                $stmt->bindParam(':username', $username);
                $stmt->execute();

                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($password, $user['password'])) {
                        // Update last login time
                        $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
                        $update_stmt->bindParam(':id', $user['id']);
                        $update_stmt->execute();

                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['last_login'] = $user['last_login'];

                        // Set session timeout to 2 hours
                        $_SESSION['expires'] = time() + (2 * 60 * 60);

                        header("Location: ../users/index.php");
                        exit();
                    } else {
                        $errors[] = "Username atau password salah.";
                    }
                } else {
                    $errors[] = "Username atau password salah.";
                }
            } catch (PDOException $e) {
                $errors[] = "Error sistem. Silakan coba lagi nanti.";
                error_log("Login error: " . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login User</h2>
        <?php if ($flash_message): ?>
            <div class="message <?php echo $flash_message['type']; ?>">
                <p><?php echo htmlspecialchars($flash_message['message']); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </form>
    </div>
</body>
</html>