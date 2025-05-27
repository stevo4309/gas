<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Joy Smart Gas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    /* Global Styles */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
      text-align: center;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    h1 {
      color: #ff6600;
      font-size: 36px;
      margin-bottom: 20px;
    }

    p {
      font-size: 16px;
      color: #555;
      line-height: 1.6;
      margin-bottom: 40px;
    }

    /* Card Section */
    .cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }

    .card {
      background: white;
      padding: 30px;
      width: 280px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease-in-out;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 20px;
    }

    .card h3 {
      font-size: 22px;
      color: #333;
      margin-bottom: 15px;
    }

    .card p {
      font-size: 14px;
      color: #777;
    }

    /* Button Style */
    .card a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #ff6600;
      color: white;
      font-weight: bold;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .card a:hover {
      background-color: #e65c00;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .cards {
        flex-direction: column;
        align-items: center;
      }

      .card {
        width: 90%;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Welcome to Joy Smart Gas</h1>
  <p>Your trusted partner in providing safe, reliable, and affordable cooking gas solutions. We strive to bring you convenience, quality, and safety with every delivery.</p>

  <div class="cards">
    <!-- Who We Are -->
    <div class="card">
      <img src="images/about/who-we-are.jpg" alt="Who We Are">
      <h3>Who We Are</h3>
      <p>We are a trusted gas supplier focused on delivering premium quality cooking gas for homes and businesses across Nairobi.</p>
      <a href="#who-we-are">Learn More</a>
    </div>

    <!-- What We Offer -->
    <div class="card">
      <img src="images/about/what-we-offer.jpg" alt="What We Offer">
      <h3>What We Offer</h3>
      <p>Gas refills, complete cylinders, accessories, and safe doorstep delivery to ensure that you never run out of cooking gas.</p>
      <a href="products.php">Place an Order</a>
    </div>                 

    <!-- Why Choose Us -->
    <div class="card">
      <img src="images/about/why-choose-us.jpg" alt="Why Choose Us">
      <h3>Why Choose Us</h3>
      <p>We ensure safety, affordability, and efficiency. Our quick and reliable service ensures that your cooking experience is smooth and hassle-free.</p>
      <a href="contact.php">Get In Touch</a>
    </div>
           
    <!-- Safety Commitment -->
    <div class="card">
      <img src="images/about/safety.jpg" alt="Safety Commitment">
      <h3>Our Safety Commitment</h3>
      <p>We adhere to the highest safety standards and follow strict protocols to ensure that our products are always safe for use.</p>
      <a href="#safety.html">Learn More</a>
    </div>

    <!-- Contact Us -->
    <div class="card">
      <img src="images/about/contact.jpg" alt="Contact Us">
      <h3>Get in Touch</h3>
      <p>We are available for quick orders, inquiries, and assistance. Reach out to us and we will be happy to assist you.</p>
      <a href="contact.php">Contact Us</a>
    </div>
  </div>
</div>

</body>
</html>
