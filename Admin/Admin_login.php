<?php
session_start();
require_once '../db_connection.php'; // Ensure this path is correct

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // You can use password_verify() if passwords are hashed
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php'); // Adjust to your admin landing page
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin user not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Admin Login</title></head>
<body>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required /><br/>
        <input type="password" name="password" placeholder="Password" required /><br/>
        <button type="submit">Login</button>
    </form>
</body>
</html>
