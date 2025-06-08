<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Not logged in, redirect to login page
    header('Location: Admin_login.php'); 
    exit();
}

// If logged in, continue loading the requested page
$page = $_GET['page'] ?? 'dashboard.php';

// Sanitize to avoid path traversal attacks
$allowed_pages = ['dashboard.php', 'other_page.php']; // add allowed pages here
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard.php';
}

include $page;
?>
