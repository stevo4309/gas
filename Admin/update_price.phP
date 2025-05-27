<?php
// Include database connection
include('../db_connection.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_price'])) {
    // Get product ID and new price from the form
    $product_id = $_POST['product_id'];
    $new_price_6kg = $_POST['price_6kg'];
    $new_price_13kg = $_POST['price_13kg'];

    // Update the price for the 6kg and 13kg in the database
    $sql = "UPDATE gas_products SET price_6kg = '$new_price_6kg', price_13kg = '$new_price_13kg' WHERE id = '$product_id'";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to the manage page with a success message
        header("Location: manage_refill.php?success=Price updated successfully.");
    } else {
        // Handle any errors during the update
        echo "Error updating price: " . mysqli_error($conn);
    }
}
?>

<!-- HTML Form to Update the Price -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Gas Product Price - Joy Smart Gas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Gas Product Price</h2>

    <?php
    // Get the product ID from the URL
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];

        // Fetch the current prices from the database
        $sql = "SELECT * FROM gas_products WHERE id = '$product_id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $product = mysqli_fetch_assoc($result);
            $price_6kg = $product['price_6kg'];
            $price_13kg = $product['price_13kg'];
        } else {
            echo "Product not found.";
            exit;
        }
    }
    ?>

    <!-- Update Price Form -->
    <form action="update_price.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $product_id ?>">

        <label for="price_6kg">Price for 6kg (Ksh):</label>
        <input type="number" id="price_6kg" name="price_6kg" value="<?= $price_6kg ?>" required>

        <label for="price_13kg">Price for 13kg (Ksh):</label>
        <input type="number" id="price_13kg" name="price_13kg" value="<?= $price_13kg ?>" required>

        <input type="submit" name="update_price" value="Update Price" class="btn">
    </form>
</div>

</body>
</html>
