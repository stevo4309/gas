<?php
// Include database connection
include('../db_connection.php');

// Add Product Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price_6kg = $_POST['price_6kg'];
    $price_13kg = $_POST['price_13kg'];
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($imageTmp, "images/" . $image);

    $sql = "INSERT INTO gas_products (name, image, price_6kg, price_13kg) 
            VALUES ('$name', '$image', '$price_6kg', '$price_13kg')";
    if (!mysqli_query($conn, $sql)) {
        echo "Error: " . mysqli_error($conn);
    }
}

// Update All Prices Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_prices'])) {
    $new_price_6kg = $_POST['price_6kg'];
    $new_price_13kg = $_POST['price_13kg'];
    $update_sql = "UPDATE gas_products SET price_6kg = '$new_price_6kg', price_13kg = '$new_price_13kg'";
    if (!mysqli_query($conn, $update_sql)) {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete Product Logic
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    $delete_sql = "DELETE FROM gas_products WHERE id = '$id_to_delete'";
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Get product to edit
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_result = mysqli_query($conn, "SELECT * FROM gas_products WHERE id = '$edit_id'");
    if ($edit_result && mysqli_num_rows($edit_result) > 0) {
        $edit_product = mysqli_fetch_assoc($edit_result);
    }
}

// Update specific product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $id = $_POST['edit_id'];
    $name = $_POST['name'];
    $price_6kg = $_POST['price_6kg'];
    $price_13kg = $_POST['price_13kg'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($imageTmp, "images/" . $image);
        $sql = "UPDATE gas_products SET name='$name', price_6kg='$price_6kg', price_13kg='$price_13kg', image='$image' WHERE id='$id'";
    } else {
        $sql = "UPDATE gas_products SET name='$name', price_6kg='$price_6kg', price_13kg='$price_13kg' WHERE id='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gas Products Management - Joy Smart Gas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        td img {
            width: 80px;
            height: auto;
            border-radius: 5px;
        }
        select, input {
            padding: 8px;
            font-size: 14px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #aaa;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage refills</h2>

    <!-- Update Prices Form -->
    <form action="" method="POST">
        <h3>Update Refilling Prices for All Products</h3>
        <label for="price_6kg">New Price for 6kg (Ksh):</label>
        <input type="number" id="price_6kg" name="price_6kg" required><br><br>

        <label for="price_13kg">New Price for 13kg (Ksh):</label>
        <input type="number" id="price_13kg" name="price_13kg" required><br><br>

        <input type="submit" name="update_prices" value="Update Prices" class="btn" style="background-color: #28a745;">
    </form>

    <hr>

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

    <hr>

    <!-- Display Gas Products Table -->
    <table>
        <tr>
            <th>Image</th>
            <th>Brand</th>
            <th>Price (6kg)</th>
            <th>Price (13kg)</th>
            <th>Action</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM gas_products");
        while ($brand = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><img src="images/<?= $brand['image'] ?>" alt="<?= htmlspecialchars($brand['name']) ?>"></td>
                <td><?= htmlspecialchars($brand['name']) ?></td>
                <td><?= number_format($brand['price_6kg']) ?></td>
                <td><?= number_format($brand['price_13kg']) ?></td>
                <td>
                    <a href="?edit=<?= $brand['id'] ?>" class="btn" style="background-color: #ffc107;">Edit</a>
                    <a href="?delete=<?= $brand['id'] ?>" class="btn" style="background-color: #dc3545;">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Edit Product Modal -->
<?php if ($edit_product): ?>
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Edit Product: <?= htmlspecialchars($edit_product['name']) ?></h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="edit_id" value="<?= $edit_product['id'] ?>">
            <label>Product Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_product['name']) ?>" required><br><br>

            <label>Change Image (optional):</label>
            <input type="file" name="image" accept="image/*"><br><br>

            <label>Price for 6kg (Ksh):</label>
            <input type="number" name="price_6kg" value="<?= $edit_product['price_6kg'] ?>" required><br><br>

            <label>Price for 13kg (Ksh):</label>
            <input type="number" name="price_13kg" value="<?= $edit_product['price_13kg'] ?>" required><br><br>

            <input type="submit" name="update_product" value="Update Product" class="btn" style="background-color: #17a2b8;">
            <button type="button" class="btn" style="background-color: grey;" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>
<script>
    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
        window.history.pushState({}, document.title, window.location.pathname);
    }
    document.getElementById('editModal').style.display = 'block';
</script>
<?php endif; ?>

</body>
</html>
