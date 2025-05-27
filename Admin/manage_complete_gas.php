<?php
include('../db_connection.php');

// Delete Product Functionality
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Fetch the image name before deletion
    $sql = "SELECT image FROM complete_gas_products WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $image_name = $row['image'];
        
        // Delete the image from the server
        $image_path = "images/" . $image_name;
        if (file_exists($image_path)) {
            unlink($image_path); // Deletes the image file
        }
        
        // Delete the product from the database
        $sql_delete = "DELETE FROM complete_gas_products WHERE id = '$id'";
        if (mysqli_query($conn, $sql_delete)) {
            echo "<script>alert('Product deleted successfully!'); window.location.href='manage_complete_gas.php';</script>";
        } else {
            echo "Error deleting product: " . mysqli_error($conn);
        }
    } else {
        echo "Product not found!";
    }
}

// Add New Product Functionality
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price_6kg = mysqli_real_escape_string($conn, $_POST['price_6kg']);
    $price_13kg = mysqli_real_escape_string($conn, $_POST['price_13kg']);
    
    // Image handling
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = "images/" . $image;

    // Move the uploaded image to the images folder
    if (move_uploaded_file($image_tmp, $image_folder)) {
        // Insert the new product into the database
        $sql = "INSERT INTO complete_gas_products (name, price_6kg, price_13kg, image) 
                VALUES ('$name', '$price_6kg', '$price_13kg', '$image')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Product added successfully!'); window.location.href='manage_complete_gas.php';</script>";
        } else {
            echo "Error adding product: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading image.";
    }
}

// Fetch all complete gas products
$sql = "SELECT * FROM complete_gas_products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complete Gas Cylinders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #333;
            margin: 20px 0;
            font-size: 32px;
            font-weight: 600;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #444;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: 600;
        }

        td img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        td a {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 6px 14px;
            font-size: 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        td a:hover {
            background-color: #0056b3;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        form {
            margin: 20px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 60%;
            margin: 20px auto;
        }

        form label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        form input[type="text"], form input[type="number"], form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            border: none;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Modal Style */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-footer {
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Add Product Form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Add New Product</h3>
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <label for="price_6kg">Price for 6kg (Ksh):</label>
        <input type="number" id="price_6kg" name="price_6kg" required><br><br>

        <label for="price_13kg">Price for 13kg (Ksh):</label>
        <input type="number" id="price_13kg" name="price_13kg" required><br><br>

        <input type="submit" name="add_product" value="Add Product" class="btn">
    </form>

    <div class="container">
        <h2>Manage Complete Gas Cylinders</h2>

        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Brand</th>
                    <th>Price for 6kg (Ksh)</th>
                    <th>Price for 13kg (Ksh)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><img src="images/<?= htmlspecialchars($row['image']) ?>" alt="Brand Image"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= number_format($row['price_6kg'], 2) ?></td>
                        <td><?= number_format($row['price_13kg'], 2) ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['id'] ?>" class="edit-btn"><i class="fas fa-pencil-alt"></i> Edit</a>
                            <a href="?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return showModal(<?= $row['id'] ?>)"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Are you sure you want to delete this product?</h2>
            <div class="modal-footer">
                <button class="btn" id="confirmDelete">Yes, Delete</button>
                <button class="btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function showModal(id) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('confirmDelete').onclick = function() {
                window.location.href = '?delete=' + id;
            }
            return false;
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>

</body>
</html>
