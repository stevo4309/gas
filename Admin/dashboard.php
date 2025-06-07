<?php
// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Not logged in, redirect to login page
    header("Location: admin_login.php"); // or relative path if needed
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
      --primary-color: #00d1b2;
      --dark-bg: #1c2533;
      --light-bg: #ffffff;<!DOCTYPE html>
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

    /* Sidebar */
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
      transition: transform 0.3s ease-in-out;
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

    /* Main Content */
    .main-content {
      margin-left: 270px;
      padding: 40px 20px;
      min-height: 100vh;
      transition: margin-left 0.3s ease-in-out;
    }

    .main-content h1 {
      font-size: 32px;
      color: var(--text-dark);
      margin-bottom: 30px;
    }

    /* Cards container and cards */
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

    /* Hamburger menu button */
    .hamburger {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background: var(--primary-color);
      border: none;
      padding: 10px 12px;
      border-radius: 5px;
      cursor: pointer;
      z-index: 1100;
    }

    .hamburger div {
      width: 25px;
      height: 3px;
      background-color: white;
      margin: 5px 0;
      border-radius: 2px;
      transition: 0.3s;
    }

    /* Mobile Styles */
    @media screen and (max-width: 768px) {
      .sidebar {
        position: fixed;
        height: 100%;
        top: 0;
        left: 0;
        transform: translateX(-100%);
        width: 250px;
        transition: transform 0.3s ease-in-out;
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
        padding: 70px 20px 20px;
      }

      .cards-container {
        flex-direction: column;
      }

      /* Show hamburger */
      .hamburger {
        display: block;
      }
    }

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
  <h1>Welcome, Admin!</h1>

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

  // Optional: close sidebar on clicking a sidebar link (mobile)
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

    /* Sidebar */
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
      transition: transform 0.3s ease-in-out;
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

    /* Main Content */
    .main-content {
      margin-left: 270px;
      padding: 40px 20px;
      min-height: 100vh;
      transition: margin-left 0.3s ease-in-out;
    }

    .main-content h1 {
      font-size: 32px;
      color: var(--text-dark);
      margin-bottom: 30px;
    }

    /* Cards container and cards */
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

    /* Hamburger menu button */
    .hamburger {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      background: var(--primary-color);
      border: none;
      padding: 10px 12px;
      border-radius: 5px;
      cursor: pointer;
      z-index: 1100;
    }

    .hamburger div {
      width: 25px;
      height: 3px;
      background-color: white;
      margin: 5px 0;
      border-radius: 2px;
      transition: 0.3s;
    }

    /* Mobile Styles */
    @media screen and (max-width: 768px) {
      .sidebar {
        position: fixed;
        height: 100%;
        top: 0;
        left: 0;
        transform: translateX(-100%);
        width: 250px;
        transition: transform 0.3s ease-in-out;
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
        padding: 70px 20px 20px;
      }

      .cards-container {
        flex-direction: column;
      }

      /* Show hamburger */
      .hamburger {
        display: block;
      }
    }

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

  // Optional: close sidebar on clicking a sidebar link (mobile)
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
