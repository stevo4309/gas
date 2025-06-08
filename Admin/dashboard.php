<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: Admin_login.php');
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
    :root {
      --primary-color: #00b894;
      --dark-bg: #2c3e50;
      --light-bg: #ecf0f1;
      --text-dark: #2d3436;
      --white: #ffffff;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-bg);
      color: var(--text-dark);
      min-height: 100vh;
    }

    .hamburger {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background-color: var(--primary-color);
      border: none;
      padding: 10px;
      border-radius: 4px;
      z-index: 1001;
      cursor: pointer;
    }

    .hamburger div {
      width: 25px;
      height: 3px;
      background-color: white;
      margin: 4px 0;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      height: 100%;
      background-color: var(--dark-bg);
      color: white;
      padding: 20px;
      transition: transform 0.3s ease-in-out;
      z-index: 1000;
    }

    .sidebar h2 {
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin: 15px 0;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      display: block;
      padding: 10px;
      border-radius: 4px;
      transition: background 0.2s;
    }

    .sidebar ul li a:hover {
      background-color: var(--primary-color);
      color: var(--dark-bg);
    }

    .main-content {
      margin-left: 270px;
      padding: 40px 20px;
      transition: margin-left 0.3s ease-in-out;
    }

    .main-content h1 {
      font-size: 28px;
      margin-bottom: 25px;
    }

    .cards-container {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .card {
      background-color: var(--white);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      flex: 1;
      min-width: 200px;
    }

    .card h3 {
      margin-bottom: 10px;
      font-size: 18px;
      color: var(--text-dark);
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
      color: var(--primary-color);
    }

    .card.low-stock p {
      color: red;
    }

    @media (max-width: 768px) {
      .hamburger {
        display: block;
      }

      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
        padding: 70px 15px 20px;
      }

      .cards-container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<!-- Hamburger for mobile -->
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

<!-- Main content -->
<div class="main-content">
  <h1>Welcome, <?= isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin' ?>!</h1>

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
