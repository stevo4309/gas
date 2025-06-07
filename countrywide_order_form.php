<?php
session_start();
require 'db_connection.php';
require 'send_email.php';

$customer_name = $phone_number = $county = $subcounty = $delivery_address = $payment_method = "";
$errorMessage = $successMessage = "";

$cartType = $_GET['type'] ?? '';
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
    $county = trim($_POST["county"] ?? '');
    $subcounty = trim($_POST["subcounty"] ?? '');
    $delivery_address = trim($_POST["delivery_address"] ?? '');
    $payment_method = trim($_POST["payment_method"] ?? '');

    if (empty($customer_name) || empty($phone_number) || empty($county) || empty($subcounty) || empty($delivery_address) || empty($payment_method) || empty($cartItems)) {
        $errorMessage = "All fields and cart items are required.";
    } else {
        $items_json = json_encode($cartItems);
        $sql = "INSERT INTO orders (items, customer_name, phone_number, county, subcounty, delivery_address, payment_method)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $items_json, $customer_name, $phone_number, $county, $subcounty, $delivery_address, $payment_method);

        if ($stmt->execute()) {
            $successMessage = "Order Sent Successfully!";

            $emailBody = "New order details:\n\n"
                . "Customer Name: $customer_name\n"
                . "Phone Number: $phone_number\n"
                . "County: $county\n"
                . "Sub-county / Town: $subcounty\n"
                . "Exact Address: $delivery_address\n"
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 1rem;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }

        h2, h3 {
            text-align: center;
            margin-top: 0;
            color: #333;
        }

        input, textarea, select, button {
            width: 100%;
            padding: 0.8rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .error-message, .success-message {
            text-align: center;
            font-size: 1rem;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 6px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        ul li {
            padding: 0.4rem 0;
            border-bottom: 1px solid #eee;
            font-size: 1rem;
        }

        @media (max-width: 600px) {
            body { padding: 0.5rem; }
            .container { padding: 1rem; }
            h2, h3 { font-size: 1.5rem; }
            input, textarea, select, button { font-size: 1rem; padding: 0.7rem; }
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
        <form id="orderForm" action="?type=<?= htmlspecialchars($cartType) ?>" method="POST">
            <input type="hidden" id="cartCount" value="<?= count($cartItems) ?>">

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
                <option value="Cash">Cash on Delivery</option>
                <option value="MPesa">MPesa</option>
                <option value="Card">Card</option>
            </select>

            <button type="submit">Confirm Order</button>
        </form>
    <?php endif; ?>
</div>

<script>
    document.getElementById("orderForm")?.addEventListener("submit", function(event) {
        const cartCount = parseInt(document.getElementById("cartCount").value);
        if (cartCount <= 0) {
            event.preventDefault();
            alert("Your cart is empty. Please add items before confirming your order.");
        }
    });
</script>

</body>
</html>
