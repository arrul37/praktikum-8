<?php
session_start();

// If user is logged in, redirect to users dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: users/index.php");
    exit();
}

// Otherwise, redirect to login page
header("Location: auth/login.php");
exit();
?>
