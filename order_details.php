<?php
session_start();
require 'db_connection.php';
require 'send_email.php';

$customer_name = $phone_number = $apartment_name = $location = $location_details = $payment_method = "";
$errorMessage = $successMessage = "";

$cartType = $_GET['type'] ?? '';
$locationFromUrl = $_GET['location'] ?? '';
$cartItems = [];

switch ($cartType) {
    case 'accessories':
        $cartItems = $_SESSION['accessories_cart'] ?? [];
        break;
    case 'complete_gas':
        $cartItems = $_SESSION['complete_cart'] ?? [];
        break;
    case 'refill':
        $cartItems = $_SESSION['refill_cart'] ?? [];
        break;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_name = trim($_POST["customer_name"] ?? '');
    $phone_number = trim($_POST["phone_number"] ?? '');
    $apartment_name = trim($_POST["apartment_name"] ?? '');
    $location = trim($_POST["location"] ?? $locationFromUrl);
    $location_details = trim($_POST["location_details"] ?? '');
    $payment_method = trim($_POST["payment_method"] ?? '');

    if (empty($customer_name) || empty($phone_number) || empty($location) || empty($payment_method) || empty($cartItems)) {
        $errorMessage = "All fields and cart items are required.";
    } else {
        $items_json = json_encode($cartItems);
        $sql = "INSERT INTO orders (items, customer_name, phone_number, apartment_name, location, location_details, payment_method)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $items_json, $customer_name, $phone_number, $apartment_name, $location, $location_details, $payment_method);

        if ($stmt->execute()) {
            $successMessage = "Order Sent Successfully!";

            $emailBody = "New order details:\n\n"
                . "Customer Name: $customer_name\n"
                . "Phone Number: $phone_number\n"
                . "Apartment: $apartment_name\n"
                . "Location: $location\n"
                . "Location Details: $location_details\n"
                . "Payment Method: $payment_method\n\n"
                . "Ordered Items:\n";

            $total = 0;
            foreach ($cartItems as $item) {
                $itemName = htmlspecialchars($item['name'] ?? $item['product'] ?? 'Unknown');
                $itemPrice = (float)($item['price'] ?? 0);
                $total += $itemPrice;
                $emailBody .= "- $itemName: Ksh " . number_format($itemPrice) . "\n";
            }

            $emailBody .= "\nTotal: Ksh " . number_format($total);
            sendOrderEmail('joysmartgas@gmail.com', 'New Order - Joy Smart Gas', $emailBody);

            switch ($cartType) {
                case 'accessories':
                    unset($_SESSION['accessories_cart']);
                    break;
                case 'complete_gas':
                    unset($_SESSION['complete_cart']);
                    break;
                case 'refill':
                    unset($_SESSION['refill_cart']);
                    break;
            }

        } else {
            $errorMessage = "Error saving order: " . $conn->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            text-align: center;
            font-size: 1.5rem;
        }

        input, textarea, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        textarea {
            height: 80px;
            resize: vertical;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            padding: 5px 0;
            font-size: 1rem;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
                border-radius: 8px;
            }

            h2, h3 {
                font-size: 1.3rem;
            }

            input, textarea, select, button {
                padding: 10px;
                font-size: 0.95rem;
            }

            button {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Confirm Your Order</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="error-message"><?= $errorMessage ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="success-message"><?= $successMessage ?></div>
    <?php endif; ?>

    <?php if (!empty($cartItems)): ?>
        <h3>Order Summary</h3>
        <ul>
            <?php $total = 0; ?>
            <?php foreach ($cartItems as $item):
                $itemName = htmlspecialchars($item['name'] ?? $item['product'] ?? '');
                $itemPrice = (float)($item['price'] ?? 0);
                $total += $itemPrice;
            ?>
                <li><?= $itemName ?> — Ksh <?= number_format($itemPrice) ?></li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total: </strong>Ksh <?= number_format($total) ?></p>
    <?php else: ?>
        <p>No items found in your cart.</p>
    <?php endif; ?>

    <?php if (empty($successMessage)): ?>
        <form id="orderForm" action="?type=<?= htmlspecialchars($cartType) ?>&location=<?= urlencode($locationFromUrl) ?>" method="POST">
            <input type="hidden" id="cartCount" value="<?= count($cartItems) ?>">

            <label>Full Name:</label>
            <input type="text" name="customer_name" value="<?= htmlspecialchars($customer_name) ?>" required>

            <label>Phone Number:</label>
            <input type="tel" name="phone_number" value="<?= htmlspecialchars($phone_number) ?>" required>

            <label>Apartment Name:</label>
            <input type="text" name="apartment_name" value="<?= htmlspecialchars($apartment_name) ?>">

            <label>Delivery Location (Ruiru Areas):</label>
            <select name="location" required>
                <option value="">-- Select Location --</option>
                <?php
                $ruiru_areas = [
                    'Ruiru Town', 'Kamakis', 'Gatongora', 'Kwa Kairu', 'Membley', 'Bati', 'Gwa Kairu',
                    'Mugutha', 'Rainbow', 'Kimbo', 'Kihunguro', 'Ruiru East', 'Ruiru Bypass',
                    'Ruiru West', 'Toll Station', 'Mwalimu Farm', 'Kamakis Bypass'
                ];
                foreach ($ruiru_areas as $area) {
                    $selected = ($location === $area || $locationFromUrl === $area) ? 'selected' : '';
                    echo "<option value=\"$area\" $selected>$area</option>";
                }
                ?>
            </select>

            <label>Additional Location Details:</label>
            <textarea name="location_details" placeholder="Floor, House Number, Landmark"><?= htmlspecialchars($location_details) ?></textarea>

            <label>Payment Method:</label>
            <select name="payment_method" required>
                <option value="">-- Select Payment Method --</option>
                <option value="Cash" <?= ($payment_method == 'Cash') ? 'selected' : '' ?>>Cash on Delivery</option>
                <option value="MPesa" <?= ($payment_method == 'MPesa') ? 'selected' : '' ?>>MPesa</option>
                <option value="Card" <?= ($payment_method == 'Card') ? 'selected' : '' ?>>Card</option>
            </select>

            <button type="submit">Confirm Order</button>
        </form>
    <?php endif; ?>
</div>

<script>
    document.getElementById("orderForm").addEventListener("submit", function(event) {
        const cartCount = parseInt(document.getElementById("cartCount").value);
        if (cartCount <= 0) {
            event.preventDefault();
            alert("Your cart is empty. Please add items before confirming your order.");
        }
    });
</script>

</body>
</html>
