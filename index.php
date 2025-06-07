<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = rand(1000, 9999);
}
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Joy Smart Gas</title>
  <meta name="description" content="Joy Smart Gas provides fast, free delivery for cooking gas in Nairobi.">
  <meta name="keywords" content="Gas delivery, Cooking gas, Nairobi gas, Free gas delivery">
  <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', sans-serif;
    }

    header {
      background-color: #ec0d0d;
      color: white;
      padding: 16px 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .modern-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: auto;
      position: relative;
    }

    .logo {
      display: flex;
      gap: 4px;
      flex-wrap: wrap;
    }

    .logo span {
      display: inline-block;
      background-color: white;
      color: black;
      font-size: 2em;
      padding: 10px;
      font-weight: bold;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .nav-menu {
      display: flex;
      gap: 24px;
    }

    .nav-menu a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      padding: 8px 12px;
      border-radius: 4px;
    }

    .nav-menu a:hover {
      background-color: white;
      color: #ec0d0d;
    }

    .menu-icon {
      display: none;
      font-size: 28px;
      cursor: pointer;
      user-select: none;
    }

    #menu-toggle {
      display: none;
    }

    @media (max-width: 768px) {
      .menu-icon {
        display: block;
        color: white;
      }

      .nav-menu {
        flex-direction: column;
        position: absolute;
        top: 60px;
        right: 20px;
        background: rgb(240, 199, 199);
        width: 200px;
        display: none;
        padding: 10px 0;
        border-radius: 8px;
      }

      #menu-toggle:checked + .menu-icon + .nav-menu {
        display: flex;
      }
    }

    .hero {
      background: #fefefe;
      padding: 60px 20px;
      text-align: center;
    }

    .hero-content h2 {
      font-size: 2.2em;
      color: #333;
      margin-bottom: 20px;
    }

    .hero-content p {
      font-size: 1.1em;
      color: #555;
      margin-bottom: 20px;
    }

    .btn.primary-btn {
      padding: 12px 24px;
      background-color: #ec0d0d;
      color: white;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }

    .shop-section img.shop-image {
      width: 100%;
      display: block;
      max-width: 900px;
      margin: 20px auto;
      border-radius: 12px;
    }

    .about {
      background: #f9f9f9;
      padding: 60px 20px;
      text-align: center;
    }

    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 40px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .card img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }

    .cta {
      background-color: #ec0d0d;
      color: white;
      text-align: center;
      padding: 40px 20px;
    }

    .cta .btn {
      margin-top: 20px;
      background-color: white;
      color: #ec0d0d;
      padding: 12px 24px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 6px;
    }

    footer {
      text-align: center;
      background-color: #222;
      color: #ccc;
      padding: 20px;
    }
  </style>
</head>
<body>

<header>
  <div class="modern-header">
    <div class="logo">
      <?php
        $brand = "JOYSMART GAS";
        foreach (str_split($brand) as $letter) {
            echo "<span>" . htmlspecialchars($letter) . "</span>";
        }
      ?>
    </div>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">&#9776;</label>
    <nav class="nav-menu">
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="contact.php">Contact</a>
      <a href="about.php">About</a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-content">
    <h2>Fast & Reliable Cooking Gas Delivery</h2>
    <p>Order now and enjoy fast, free delivery within Ruiru! We also sell complete gas cylinders and deliver across Kenya.</p>
    <a href="products.php" class="btn primary-btn">Order Now</a>
  </div>
</section>

<section class="shop-section">
  <div class="container">
    <img src="images/shop-image.jpg" alt="Joy Smart Gas Shop" class="shop-image">
  </div>
</section>

<section class="about">
  <div class="container">
    <h2>Why Choose Joy Smart Gas?</h2>
    <p>We are committed to providing <strong>safe, fast, and affordable gas delivery</strong> to households and businesses across Ruiru.</p>

    <div class="cards-container">
      <div class="card">
        <img src="images/about/who-we-are.jpg" alt="Who We Are">
        <h3>Who We Are</h3>
        <p>Your reliable gas supplier for safe and efficient delivery.</p>
      </div>
      <div class="card">
        <img src="images/about/what-we-offer.jpg" alt="What We Offer">
        <h3>What We Offer</h3>
        <p>Gas refills, cylinders, accessories, and door-to-door delivery.</p>
      </div>
      <div class="card">
        <img src="images/about/why-choose-us.jpg" alt="Why Choose Us">
        <h3>Why Choose Us</h3>
        <p>Affordability, quick delivery, and uncompromising safety.</p>
      </div>
      <div class="card">
        <img src="images/about/safety.jpg" alt="Our Safety Commitment">
        <h3>Our Safety Commitment</h3>
        <p>Strict measures to ensure you receive the highest quality gas products.</p>
      </div>
    </div>
  </div>
</section>

<section class="cta">
  <div class="container">
    <h2>Need a Gas Refill?</h2>
    <p>Order now and enjoy fast, free delivery within Ruiru! We also sell complete gas cylinders and deliver across Kenya.</p>
    <a href="contact.php" class="btn">Contact Us</a>
  </div>
</section>

<footer>
  <p>© 2025 Joy Smart Gas. All rights reserved.</p>
</footer>

</body>
</html>
