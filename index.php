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
      line-height: 1.6;
    }

    header {
      background: rgb(236, 13, 13);
      color: #fff;
    }

    .header-container {
      display: flex;
      flex-wrap: wrap; /* allow wrapping */
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: auto;
      padding: 20px;
    }

    .logo-name {
      font-size: 28px;
      font-weight: bold;
      color: #051d5f;
      -webkit-text-stroke: 1.2px white;
      flex: 1 1 200px; /* grow/shrink, basis */
    }

    nav {
      flex: 1 1 300px; /* grow/shrink, basis */
    }

    nav ul {
      list-style: none;
      display: flex;
      flex-wrap: wrap; /* allow nav items to wrap to next line */
      gap: 30px;
      justify-content: flex-end; /* align nav items right */
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      font-size: 18px;
      padding: 8px 12px;
      border-radius: 4px;
      transition: background-color 0.3s ease, color 0.3s ease;
      display: inline-block;
    }

    nav ul li a:hover {
      background-color: white;
      color: #ec0d0d;
      box-shadow: 0 4px 8px rgba(236, 13, 13, 0.4);
    }

    .hero {
      background-color: #f5f5f5;
      text-align: center;
      padding: 60px 20px;
    }

    .hero h2 {
      font-size: 32px;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .btn {
      text-decoration: none;
      padding: 12px 24px;
      border-radius: 4px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .primary-btn {
      background-color: #007bff;
      color: white;
    }

    .primary-btn:hover {
      background-color: #0056b3;
    }

    .shop-section .container {
      text-align: center;
      padding: 40px 20px;
    }

    .shop-image {
      max-width: 100%;
      height: auto;
      border-radius: 12px;
    }

    .about {
      padding: 60px 20px;
      background-color: #f8f8f8;
    }

    .about h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    /* Cards container: use flexbox for 2 per row on mobile/tablets */
    .cards-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      max-width: 1200px;
      margin: auto;
      padding: 0 20px;
    }

    .card {
      background: white;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      flex: 1 1 calc(25% - 20px); /* default 4 cards per row on large screens */
      max-width: calc(25% - 20px);
    }

    .card img {
      width: 100%;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    /* Tablet and mobile: 2 cards per row */
    @media (max-width: 992px) {
      .card {
        flex: 1 1 calc(50% - 20px);
        max-width: calc(50% - 20px);
      }
    }

    /* Small phones: cards stack full width */
    @media (max-width: 480px) {
      .card {
        flex: 1 1 100%;
        max-width: 100%;
      }
    }

    .cta {
      background-color: #051d5f;
      color: white;
      padding: 40px 20px;
      text-align: center;
    }

    .cta .btn {
      background-color: white;
      color: #051d5f;
      margin-top: 15px;
    }

    .cta .btn:hover {
      background-color: #ddd;
    }

    footer {
      background-color: #222;
      color: white;
      text-align: center;
      padding: 20px;
    }
  </style>
</head>
<body>

<header>
  <div class="header-container">
    <h1 class="logo-name">JOYSMART GAS</h1>
    <nav>
      <ul>
        <li><a href="index.php">HOME</a></li>
        <li><a href="products.php">PRODUCTS</a></li>
        <li><a href="contact.php">CONTACTS</a></li>
        <li><a href="about.php">ABOUT</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-content">
    <h2>Fast & Reliable Cooking Gas Delivery</h2>
    <p>Order now and enjoy fast, free delivery within Ruiru! We also sell complete gas cylinder and delivery across Kenya.</p>
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
    <p>Order now and enjoy fast, free delivery within Ruiru! We also sell complete gas cylinder and delivery across Kenya.</p>
    <a href="contact.php" class="btn">Contact Us</a>
  </div>
</section>

<footer>
  <p>© 2025 Joy Smart Gas. All rights reserved.</p>
</footer>

</body>
</html>
