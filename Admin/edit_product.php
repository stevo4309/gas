<?php
include('../db_connection.php');

// Check if the product ID is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the product details from the database
    $sql = "SELECT * FROM complete_gas_products WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Product not found!";
        exit();
    }
} else {
    echo "No product ID provided!";
    exit();
}

// Handle updating the product
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price_6kg = $_POST['price_6kg'];
    $price_13kg = $_POST['price_13kg'];
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);

    // If a new image is uploaded, move the image to the target directory
    if ($image) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Delete the old image from the server
            $old_image = "images/" . $row['image'];
            if (file_exists($old_image)) {
                unlink($old_image);
            }

            // Update the product with the new image
            $sql = "UPDATE complete_gas_products SET name = '$name', price_6kg = '$price_6kg', price_13kg = '$price_13kg', image = '$image' WHERE id = $id";
        } else {
            echo "Error uploading the file.";
            exit();
        }
    } else {
        // If no new image is uploaded, update only other fields
        $sql = "UPDATE complete_gas_products SET name = '$name', price_6kg = '$price_6kg', price_13kg = '$price_13kg' WHERE id = $id";
    }

    // Execute the query and check for errors
    if (mysqli_query($conn, $sql)) {
        header('Location: manage_complete_gas.php'); // Correctly redirect after update
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin: 20px 0;
            font-size: 32px;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1);
        }

        form {
            margin: 20px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        form input[type="text"], form input[type="number"], form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn-container {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Product</h2>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Brand Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($row['name']) ?>" required>

            <label for="price_6kg">Price for 6kg (Ksh):</label>
            <input type="number" name="price_6kg" id="price_6kg" value="<?= $row['price_6kg'] ?>" step="0.01" required>

            <label for="price_13kg">Price for 13kg (Ksh):</label>
            <input type="number" name="price_13kg" id="price_13kg" value="<?= $row['price_13kg'] ?>" step="0.01" required>

            <label for="image">Brand Image (Leave blank if not updating):</label>
            <input type="file" name="image" id="image" accept="image/*">

            <div class="btn-container">
                <button type="submit" name="update">Update Product</button>
            </div>
        </form>
    </div>

</body>
</html>
