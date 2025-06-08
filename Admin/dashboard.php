<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: Admin_login.php'); // Redirect to your login page; adjust filename if needed
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <style>
    /* Your existing CSS here */
    /* ... */
  </style>
</head>
<body>

<!-- Hamburger menu for mobile -->
<button class="hamburger" id="hamburger" aria-label="Toggle menu">
  <div></div>
  <div></div>
  <div></div>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <h2>Admin Panel</h2>
  <ul>
    <li><a href="index.php">📊 Dashboard</a></li>
    <li><a href="manage_accessories.php">🛍 Manage Accessories</a></li>
    <li><a href="manage_complete_gas.php">🔥 Manage Gas Cylinders</a></li>
    <li><a href="manage_refill.php">⛽ Manage Refilling</a></li>
    <li><a href="admin_messages.php">📩 Admin Messages</a></li>
    <li><a href="orders.php">📦 Orders</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content">
  <h1>Welcome, <?= isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin' ?>!</h1>

  <!-- Dashboard Cards -->
  <div class="cards-container">
    <div class="card">
      <h3>📦 Total Orders</h3>
      <p>128</p>
    </div>

    <div class="card">
      <h3>📩 New Messages</h3>
      <p>5</p>
    </div>

    <div class="card low-stock">
      <h3>⚠️ Low Stock Alerts</h3>
      <p>3 Items</p>
    </div>
  </div>
</div>

<script>
  const hamburger = document.getElementById('hamburger');
  const sidebar = document.getElementById('sidebar');

  hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');
  });

  sidebar.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) {
        sidebar.classList.remove('active');
      }
    });
  });
</script>

</body>
</html>
