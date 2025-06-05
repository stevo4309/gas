<?php
session_start();
include 'db_connection.php';

// Assign session-based user_id (temporary if no login)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = rand(1000, 9999); // Guest ID
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
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    /* Header */
    header {
        background: rgb(236, 13, 13);
        color: #fff;
        padding: 30px 0;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: auto;
        padding: 0 20px;
    }

    .logo-name span {
        display: inline-block;
        font-size: 36px;
        color: #051d5f;
        -webkit-text-stroke: 1.5px white;
        font-weight: bold;
    }

    nav ul {
        list-style: none;
        display: flex;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        padding: 0;
        margin: 0;
        overflow-x: auto;
        white-space: nowrap;
    }

    nav ul li {
        margin: 0 10px;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        padding: 10px 15px;
        display: inline-block;
        transition: background-color 0.3s;
    }

    nav ul li a:hover {
        color: #333;
        background: #fff;
        border-radius: 5px;
    }

    @media (max-width: 600px) {
        .logo-name span {
            font-size: 28px;
        }

        nav ul li a {
            padding: 8px 10px;
            font-size: 14px;
        }
    }

    /* Hero Section */
    .hero {
        background-color: #f5f5f5;
        text-align: center;
        padding: 60px 20px;
    }

    .hero-content h2 {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .hero-content p {
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

    /* Shop Section */
    .shop-section .container {
        text-align: center;
        padding: 40px 20px;
    }

    .shop-image {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }

    /* About Section */
    .about {
        padding: 60px 20px;
        background-color: #f8f8f8;
    }

    .about .container h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        max-width: 1000px;
        margin: auto;
    }

    .card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .card img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    /* Call to Action */
    .cta {
        padding: 40px 20px;
        background-color: #051d5f;
        color: white;
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

    /* Footer */
    footer {
        background-color: #222;
        color: white;
        text-align: center;
        padding: 20px;
        font-size: 14px;
    }
  </style>
</head>
<body>

<header>
    <div class="container header-container">
        <div class="logo">
            <h1 class="logo-name">
                <span>J</span><span>O</span><span>Y</span>
                <span>&nbsp;</span>
                <span>S</span><span>M</span><span>A</span><span>R</span><span>T</span>
                <span>&nbsp;</span>
                <span>G</span><span>a</span><span>s</span>
            </h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
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
        <p style="text-align:center;">We are committed to providing <strong>safe, fast, and affordable gas delivery</strong> to households and businesses across Ruiru.</p>
        
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
        <a href="contact.php" class="btn secondary-btn">Contact Us</a>
    </div>
</section>

<footer>
    <div class="container">
        <p>© 2025 Joy Smart Gas. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
