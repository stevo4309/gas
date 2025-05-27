<?php
include 'db_connection.php'; // Ensure this file contains your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $brand = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
    $size = mysqli_real_escape_string($conn, $_POST['size'] ?? '');
    $price = mysqli_real_escape_string($conn, $_POST['price'] ?? '');
    $location = mysqli_real_escape_string($conn, $_POST['location'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');

    // Validate required fields
    if (empty($brand) || empty($size) || empty($price) || empty($location) || empty($phone)) {
        echo "<p style='color: red;'>All fields are required.</p>";
        exit();
    }

    // Insert order into the database
    $sql = "INSERT INTO orders (brand, size, price, location, phone) 
            VALUES ('$brand', '$size', '$price', '$location', '$phone')";

    if (mysqli_query($conn, $sql)) {
        // WhatsApp API credentials (Replace with your UltraMsg credentials)
        $instance_id = "instance111849"; // Replace with your UltraMsg instance ID
        $api_token = "rft3w17cdqpg6vwg"; // Replace with your UltraMsg API token
        $owner_phone = "254796155690"; // Shop owner's WhatsApp number (Change 0796... to international format: 254796155690)

        // Format the order message
        $message = "🚀 *New Order Received!* 🚀\n\n"
                 . "📦 *Brand:* $brand\n"
                 . "📏 *Size:* $size\n"
                 . "💰 *Price:* Ksh $price\n"
                 . "📍 *Location:* $location\n"
                 . "📞 *Customer Phone:* $phone\n\n"
                 . "✅ *Please confirm delivery.*";

        // Send WhatsApp notification
        $api_url = "https://api.ultramsg.com/$instance_id/messages/chat";
        $data = [
            'token' => $api_token,
            'to' => $owner_phone,
            'body' => $message
        ];

        // Use cURL to send the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        echo "<p style='color: green;'>Order placed successfully! ✅</p>";
        echo "<a href='index.php'>Return to Home</a>"; // Change `index.php` to your homepage
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color: red;'>No Order Data Received</p>";
}

mysqli_close($conn);
?>
