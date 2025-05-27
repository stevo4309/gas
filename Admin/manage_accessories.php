<?php
// Include database connection
include('../db_connection.php');

// Handle form submission for adding accessories
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accessory_name']) && isset($_POST['accessory_price']) && isset($_FILES['accessory_image'])) {
        if ($_FILES['accessory_image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../images/';
            $targetFile = $targetDir . basename($_FILES['accessory_image']['name']);

            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['accessory_image']['tmp_name'], $targetFile)) {
                    $name = mysqli_real_escape_string($conn, $_POST['accessory_name']);
                    $price = mysqli_real_escape_string($conn, $_POST['accessory_price']);
                    $image = basename($_FILES['accessory_image']['name']);

                    $sql = "INSERT INTO accessories (name, price, image) VALUES ('$name', '$price', '$image')";
                    if (mysqli_query($conn, $sql)) {
                        echo "<script>alert('Accessory added successfully!'); window.location.href='manage_accessories.php';</script>";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error uploading file.";
                }
            } else {
                echo "Only image files are allowed.";
            }
        } else {
            echo "No file uploaded or there was an error.";
        }
    } else {
        echo "Please fill all the required fields and select an image.";
    }
}

// Handle deletion of accessory
if (isset($_GET['delete_id'])) {
    $accessoryId = $_GET['delete_id'];
    
    // First, retrieve the image path to delete the file
    $sql = "SELECT image FROM accessories WHERE id = $accessoryId";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $imagePath = __DIR__ . '/../images/' . $row['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the image file
        }
    }

    // Delete the accessory from the database
    $sql = "DELETE FROM accessories WHERE id = $accessoryId";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Accessory deleted successfully!'); window.location.href='manage_accessories.php';</script>";
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
    <title>Manage Accessories - Joy Smart Gas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        img {
            width: 80px;
            height: auto;
            border-radius: 5px;
        }
        .action-buttons a {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .edit {
            background-color: #ffc107;
        }
        .edit:hover {
            background-color: #e0a800;
        }
        .delete {
            background-color: #dc3545;
        }
        .delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Accessories</h2>

    <!-- Form to add new accessories -->
    <form action="manage_accessories.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="accessory_name" placeholder="Enter accessory name" required>
        <input type="number" name="accessory_price" placeholder="Enter accessory price" required>
        <input type="file" name="accessory_image" required>
        <button type="submit">Add Accessory</button>
    </form>

    <h3>List of Accessories</h3>

    <!-- Display the accessories from the database -->
    <table>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price (Ksh)</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT * FROM accessories";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td><img src='../images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'></td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . number_format($row['price']) . "</td>
                    <td class='action-buttons'>
                        <a href='edit_accessory.php?id=" . $row['id'] . "' class='edit'>Edit</a>
                        <a href='manage_accessories.php?delete_id=" . $row['id'] . "' class='delete' onclick='return confirmDelete();'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No accessories found.</td></tr>";
        }
        ?>
    </table>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this accessory?");
    }
</script>

</body>
</html>
