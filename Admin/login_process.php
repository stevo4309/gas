<?php
session_start();

// Example hardcoded credentials (replace with DB check)
$admin_username = 'admin';
$admin_password = '123456';

if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: dashboard.php'); // redirect to admin dashboard
    exit();
} else {
    echo "Invalid credentials. <a href='login.php'>Try again</a>.";
}
?>
