<?php
session_start();
include('db_connection.php');

// Check that the cart isn't empty
if (empty($_SESSION['complete_cart'])) {
    die("Your cart is empty.");
}

// Get delivery location if pre-selected via GET
$location = $_GET['location'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Delivery Location - Joy Smart Gas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 15px;
        }

        select, button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            margin-top: 25px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Select Delivery Location</h2>

    <form id="locationForm">
        <label for="location">Choose Delivery Location:</label>
        <select name="location" id="location" required>
            <option value="">-- Select Location --</option>
            <option value="Ruiru" <?= $location === 'Ruiru' ? 'selected' : '' ?>>Ruiru</option>
            <option value="Other Counties" <?= $location === 'Other Counties' ? 'selected' : '' ?>>Other Counties</option>
        </select>
        <button type="submit">Continue</button>
    </form>
</div>

<script>
document.getElementById('locationForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const location = document.getElementById('location').value;
    if (!location) {
        alert('Please select a delivery location.');
        return;
    }

    let redirectUrl = '';

    if (location === "Ruiru") {
        redirectUrl = `order_details.php?type=complete_gas&location=${encodeURIComponent(location)}`;
    } else if (location === "Other Counties") {
        redirectUrl = `countrywide_order_form.php?type=complete_gas&location=${encodeURIComponent(location)}`;
    }

    window.location.href = redirectUrl;
});
</script>


</body>
</html>
