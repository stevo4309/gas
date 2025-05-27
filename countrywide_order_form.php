<?php
session_start();
require 'db_connection.php';
require 'send_email.php';

$customer_name = $phone_number = $county = $subcounty = $delivery_address = $payment_method = '';
$successMessage = $errorMessage = '';

// Load cart from session
$cart = $_SESSION['complete_cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $quantity = (int)($item['quantity'] ?? 1);
    $price = (float)($item['price'] ?? 0);
    $total += $price * $quantity;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"] ?? '';
    $phone_number = $_POST["phone_number"] ?? '';
    $county = $_POST["county"] ?? '';
    $subcounty = $_POST["subcounty"] ?? '';
    $delivery_address = $_POST["delivery_address"] ?? '';
    $payment_method = $_POST["payment_method"] ?? '';

    if (empty($cart)) {
        $errorMessage = "Your cart is empty.";
    } elseif (
        empty($customer_name) || empty($phone_number) || empty($county) || 
        empty($subcounty) || empty($delivery_address) || empty($payment_method)
    ) {
        $errorMessage = "All fields are required.";
    } else {
        foreach ($cart as $item) {
            $product = $item['name'] ?? 'Unknown';
            $brand = $item['brand'] ?? 'N/A';
            $size = $item['size'] ?? 'N/A';
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;

            $apartment_name = ''; // not used
            $location = $county . ' - ' . $subcounty;
            $location_details = $delivery_address;

            $sql = "INSERT INTO orders (product, brand, size, price, customer_name, phone_number, apartment_name, location, location_details, payment_method)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssssssssss",
                $product, $brand, $size, $price,
                $customer_name, $phone_number, $apartment_name,
                $location, $location_details, $payment_method
            );
            $stmt->execute();
            $stmt->close();

            // Send email
            $emailBody = "New countrywide order:\n\n"
                . "Customer Name: $customer_name\n"
                . "Phone Number: $phone_number\n"
                . "Product: $product\n"
                . "Brand: $brand\n"
                . "Size: $size\n"
                . "Quantity: $quantity\n"
                . "Price per Unit: Ksh $price\n"
                . "Total: Ksh " . ($price * $quantity) . "\n"
                . "County/Sub-county: $location\n"
                . "Address: $delivery_address\n"
                . "Payment Method: $payment_method";

            sendOrderEmail('joysmartgas@gmail.com', 'New Countrywide Order - Joy Smart Gas', $emailBody);
        }

        unset($_SESSION['complete_cart']);
        $successMessage = "Order placed successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Countrywide Delivery Order Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f1f3f5; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 25px; }
        label { font-weight: bold; display: block; margin-top: 15px; }
        input, select, textarea, button { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { background-color: #007bff; color: white; margin-top: 25px; border: none; }
        button:hover { background-color: #0056b3; }
        .summary { background-color: #e9ecef; padding: 15px; margin-bottom: 20px; border-radius: 5px; font-size: 15px; }
        .error-message { color: red; margin-top: 10px; }
        .success-message { color: green; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Countrywide Order</h2>

    <?php if ($errorMessage): ?>
        <div class="error-message"><?= $errorMessage ?></div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="success-message"><?= $successMessage ?></div>
    <?php endif; ?>

    <?php if (!empty($cart)): ?>
        <div class="summary">
            <strong>Order Summary:</strong><br><br>
            <?php foreach ($cart as $item): ?>
                Product: <?= htmlspecialchars($item['name'] ?? 'Unknown') ?><br>
                Brand: <?= htmlspecialchars($item['brand'] ?? 'N/A') ?><br>
                Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?><br>
                Quantity: <?= htmlspecialchars($item['quantity'] ?? 1) ?><br>
                Price per Unit: Ksh <?= number_format((float)($item['price'] ?? 0)) ?><br>
                Subtotal: Ksh <?= number_format((float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1)) ?><br><br>
            <?php endforeach; ?>
            <strong>Total: Ksh <?= number_format($total) ?></strong>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Full Name:</label>
        <input type="text" name="customer_name" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone_number" placeholder="07XXXXXXXX" pattern="07[0-9]{8}" required>

        <label for="county">County:</label>
        <input type="text" name="county" required>

        <label for="subcounty">Sub-county / Town:</label>
        <input type="text" name="subcounty" required>

        <label for="address">Exact Delivery Address:</label>
        <textarea name="delivery_address" required></textarea>

        <label>Payment Method:</label>
        <select name="payment_method" required>
            <option value="">-- Select Payment Method --</option>
            <option value="cash_on_delivery">Cash on Delivery</option>
            <option value="mpesa">MPesa</option>
        </select>

        <button type="submit">Place Order</button>
    </form>
</div>

</body>
</html>
