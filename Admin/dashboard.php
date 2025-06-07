<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <style>
    :root {
      --primary-color: #00d1b2;
      --dark-bg: #1c2533;
      --light-bg: #ffffff;
      --text-dark: #222;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body, html {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      height: 100%;
      overflow-x: hidden;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: var(--dark-bg);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px;
      z-index: 1000;
    }

    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
      text-align: center;
      color: var(--primary-color);
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin: 15px 0;
    }

    .sidebar ul li a {
      color: #ffffff;
      text-decoration: none;
      font-size: 17px;
      display: block;
      padding: 10px;
      border-radius: 5px;
      transition: background 0.3s ease;
    }

    .sidebar ul li a:hover {
      background-color: var(--primary-color);
      color: var(--dark-bg);
    }

    .main-content {
      margin-left: 270px;
      padding: 40px 20px;
      min-height: 100vh;
    }

    .main-content h1 {
      font-size: 32px;
      color: var(--text-dark);
      margin-bottom: 30px;
    }

    .cards-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      flex: 1;
      min-width: 200px;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
      color: var(--text-dark);
      margin-bottom: 10px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
      color: var(--primary-color);
    }

    .card.low-stock p {
      color: red;
    }

    @media screen and (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
      }

      .main-content {
        margin-left: 0;
        padding-top: 20px;
      }

      .cards-container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
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
  <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>

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

</body>
</html>
