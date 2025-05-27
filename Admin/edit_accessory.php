<?php
// Include database connection
include('../db_connection.php');

// Handle form submission for updating an accessory
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accessory_name']) && isset($_POST['accessory_price'])) {
        $id = $_POST['accessory_id']; // Get the accessory ID
        $name = mysqli_real_escape_string($conn, $_POST['accessory_name']);
        $price = mysqli_real_escape_string($conn, $_POST['accessory_price']);

        // Check if a new image was uploaded
        if (isset($_FILES['accessory_image']) && $_FILES['accessory_image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../images/';
            $targetFile = $targetDir . basename($_FILES['accessory_image']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['accessory_image']['tmp_name'], $targetFile)) {
                    $image = basename($_FILES['accessory_image']['name']);
                    $sql = "UPDATE accessories SET name = '$name', price = '$price', image = '$image' WHERE id = $id";
                } else {
                    echo "Error uploading file.";
                    exit;
                }
            } else {
                echo "Only image files are allowed.";
                exit;
            }
        } else {
            // If no new image was uploaded, just update name and price
            $sql = "UPDATE accessories SET name = '$name', price = '$price' WHERE id = $id";
        }

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Accessory updated successfully!'); window.location.href='manage_accessories.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch the accessory details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM accessories WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Accessory not found.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Accessory - Joy Smart Gas</title>
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
    <h2>Edit Accessory</h2>

    <!-- Form to edit accessory -->
    <form action="edit_accessory.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="accessory_id" value="<?php echo $row['id']; ?>">
        <input type="text" name="accessory_name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
        <input type="number" name="accessory_price" value="<?php echo htmlspecialchars($row['price']); ?>" required>
        <input type="file" name="accessory_image">
        <button type="submit">Update Accessory</button>
    </form>
</div>

</body>
</html>
