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

    // Map payment method values to match DB enum
    if ($payment_method === 'cash_on_delivery') {
        $payment_method = 'Cash';
    } elseif ($payment_method === 'mpesa') {
        $payment_method = 'MPesa';
    }

    if (empty($customer_name) || empty($phone_number) || empty($location) || empty($payment_method) || empty($cartItems)) {
        $errorMessage = "All fields and cart items are required.";
    } else {
        $items_json = json_encode($cartItems);
        $status = "Pending"; // Default status

        // Extract common values from first item
        $firstItem = $cartItems[0] ?? [];
        $product = $firstItem['product'] ?? $firstItem['name'] ?? 'Miscellaneous';
        $quantity = count($cartItems);
        $brand = $firstItem['brand'] ?? 'N/A';
        $size = $firstItem['size'] ?? 'N/A';
        $price = $firstItem['price'] ?? 0;

        $total_price = 0;
        foreach ($cartItems as $item) {
            $total_price += (float)($item['price'] ?? 0);
        }

        $sql = "INSERT INTO orders 
                (product, quantity, total_price, brand, size, price, customer_name, phone_number, apartment_name, location, location_details, payment_method, status, items) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidssdssssssss", 
            $product, $quantity, $total_price, $brand, $size, $price, 
            $customer_name, $phone_number, $apartment_name, $location, $location_details, $payment_method, $status, $items_json
        );

        if ($stmt->execute()) {
            $successMessage = "Order Sent Successfully!";

            // Prepare email content
            $emailBody = "New order details:\n\n"
                . "Customer Name: $customer_name\n"
                . "Phone Number: $phone_number\n"
                . "Apartment: $apartment_name\n"
                . "Location: $location\n"
                . "Location Details: $location_details\n"
                . "Payment Method: $payment_method\n\n"
                . "Ordered Items:\n";

            foreach ($cartItems as $item) {
                $itemName = htmlspecialchars($item['name'] ?? $item['product'] ?? 'Unknown');
                $itemPrice = (float)($item['price'] ?? 0);
                $emailBody .= "- $itemName: Ksh " . number_format($itemPrice) . "\n";
            }

            $emailBody .= "\nTotal: Ksh " . number_format($total_price);
            sendOrderEmail('joysmartgas@gmail.com', 'New Order - Joy Smart Gas', $emailBody);

            // Clear the cart session
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
            $errorMessage = "Error saving order: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
