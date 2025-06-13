<?php
require_once 'auth_check.php';
require_once '../config/database.php';
require_once '../config/functions.php';

// Fetch all users, ordered by creation date
try {
    $stmt = $conn->prepare("
        SELECT id, username, nama_lengkap, email, role, created_at, last_login 
        FROM users 
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching users: " . $e->getMessage());
    $page_error = "Error mengambil data user. Silakan coba beberapa saat lagi.";
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header-nav">
            <h2>Manajemen User</h2>
            <div>
                <span>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?> 
                    (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                </span>
                <a href="../index.php">Halaman Utama</a> |
                <a href="../auth/logout.php">Logout</a>
            </div>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; ?>">
                <p><?php echo htmlspecialchars($_SESSION['message']); ?></p>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <?php if (isset($page_error)): ?>
            <div class="errors">
                <p><?php echo htmlspecialchars($page_error); ?></p>
            </div>
        <?php endif; ?>

        <?php if (isAdmin()): ?>
            <p><a href="create.php" class="btn">Tambah User Baru</a></p>
        <?php endif; ?>

        <?php if (count($users) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Daftar</th>
                            <th>Login Terakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                                <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($user['created_at']))); ?></td>
                                <td>
                                    <?php 
                                    echo $user['last_login'] 
                                        ? htmlspecialchars(date('d M Y, H:i', strtotime($user['last_login'])))
                                        : 'Belum pernah login';
                                    ?>
                                </td>
                                <td>
                                    <?php if (isAdmin() || $_SESSION['user_id'] == $user['id']): ?>
                                        <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                                        
                                        <?php if (isAdmin() && $_SESSION['user_id'] != $user['id']): ?>
                                            <form action="delete.php" method="post" style="display: inline;">
                                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn-delete" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                    Hapus
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Belum ada user terdaftar.</p>
        <?php endif; ?>
    </div>
</body>
</html>