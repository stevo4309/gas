<?php
// Enable error reporting for debugging - remove or comment out in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: Admin_login.php'); 
    exit();
}

// Default page to load
$page = $_GET['page'] ?? 'dashboard.php';

// Allowed pages whitelist - make sure these files exist inside the Admin folder
$allowed_pages = ['dashboard.php', 'other_page.php'];

// Prevent path traversal by validating against allowed pages
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard.php';
}

// Include the page safely with path relative to this file
include __DIR__ . '/' . $page;
