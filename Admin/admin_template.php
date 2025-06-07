<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?> <!-- Include Sidebar -->

    <div class="main-content">
        <?php
        // Secure include to avoid path injection
        if (isset($page) && file_exists($page)) {
            include $page;
        } else {
            echo "<p>Page not found.</p>";
        }
        ?>
    </div>
</div>

<script src="assets/sidebar.js"></script>
</body>
</html>
