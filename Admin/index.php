<?php  
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
    $page = 'dashboard.php'; 
include 'admin_template.php';
}
?>
