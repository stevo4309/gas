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
/* Reset basic styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  font-family: 'Poppins', sans-serif;
}

/* Modern header layout */
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
  font-size: 24px;
  font-weight: 700;
  color: #fff;
  -webkit-text-stroke: 0.8px #051d5f;
  white-space: nowrap;
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

/* Mobile menu */
.menu-icon {
  display: none;
  font-size: 28px;
  cursor: pointer;
  user-select: none;
}

#menu-toggle {
  display: none;
}

/* Responsive */
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
    background: #ec0d0d;
    width: 200px;
    display: none;
    padding: 10px 0;
    border-radius: 8px;
  }
  #menu-toggle:checked + .menu-icon + .nav-menu {
    display: flex;
  }
  .logo {
  font-family: 'Playfair Display', serif;
  font-size: 36px;
  font-weight: 700;
  letter-spacing: 1px;
  color: white;
  background: linear-gradient(to right,rgb(0, 183, 255),rgb(89, 0, 255));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

}

  </style>
</head>
<body>

<header>
  <div class="modern-header">
    <div class="logo">JOYSMART GAS</div>
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
