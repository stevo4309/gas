<?php
session_start();
include('db_connection.php');

// Initialize complete gas cart
if (!isset($_SESSION['complete_cart'])) {
    $_SESSION['complete_cart'] = [];
}

$redirect = false;

// Add to complete gas cart (from existing products)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_complete_gas'])) {
    $product_id = (int)$_POST['product_id'];
    $size = $_POST['size'];

    $stmt = $conn->prepare("SELECT * FROM complete_gas_products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $price = ($size === '6kg') ? $product['price_6kg'] : $product['price_13kg'];
        $image = !empty($product['image']) ? $product['image'] : 'default.png';
        $item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'size' => $size,
            'price' => $price,
            'image' => $image
        ];
        $_SESSION['complete_cart'][] = $item;
        $_SESSION['success_msg'] = "Complete gas added to cart!";
    }
    $stmt->close();
    $redirect = true;
}

// Add custom gas (not in product list)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_custom_gas'])) {
    $custom_name = trim($_POST['custom_name']);
    $custom_size = $_POST['custom_size'];
    $default_price = ($custom_size === '6kg') ? 4500 : 8500;
    $default_image = 'default.png';

    if ($custom_name && in_array($custom_size, ['6kg', '13kg'])) {
        $item = [
            'id' => 0,
            'name' => $custom_name,
            'size' => $custom_size,
            'price' => $default_price,
            'image' => $default_image
        ];
        $_SESSION['complete_cart'][] = $item;
        $_SESSION['success_msg'] = "Custom gas added to cart!";
        $redirect = true;
    }
}

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_index'])) {
    $index = (int)$_POST['remove_index'];
    if (isset($_SESSION['complete_cart'][$index])) {
        unset($_SESSION['complete_cart'][$index]);
        // Re-index array after removal
        $_SESSION['complete_cart'] = array_values($_SESSION['complete_cart']);
    }
    $redirect = true;
}

if ($redirect) {
    // Debug redirect target (comment out in production)
    // error_log('Redirecting to: ' . $_SERVER['PHP_SELF']);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$success_msg = '';
if (isset($_SESSION['success_msg'])) {
    $success_msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

// Fetch products from DB
$products = mysqli_query($conn, "SELECT * FROM complete_gas_products");
if (!$products) {
    die("Error fetching products: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Gas Cylinders - Joy Smart Gas</title>
    <style>
        /* Styles unchanged - no need to modify */
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            margin: 0;
            padding-right: 260px;
        }
        .cart-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            overflow-y: auto;
            z-index: 100;
        }
        .cart-sidebar h3 {
            color: #ffc107;
            text-align: center;
        }
        .cart-item {
            margin-bottom: 15px;
            border-bottom: 1px solid #666;
            padding-bottom: 10px;
        }
        .cart-item img {
            width: 40px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .cart-item small {
            display: block;
            color: #ccc;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
        .product {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            background: #fafafa;
            text-align: center;
        }
        .product img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        .product h4 {
            margin: 10px 0;
            color: #007bff;
        }
        .product select, .product button, .product input {
            margin-top: 10px;
            padding: 8px;
            width: 100%;
            border-radius: 5px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-remove {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-order {
            display: inline-block;
            background-color: #ffc107;
            color: #212529;
            text-align: center;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
        }
        .btn-order:hover {
            background-color: #e0a800;
            color: white;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
        .product.custom {
            border: 2px dashed #aaa;
            background-color: #fff7e6;
        }
    </style>
</head>
<body>

<!-- Cart Sidebar -->
<div class="cart-sidebar">
    <h3>Complete Gas Cart</h3>
    <?php if (empty($_SESSION['complete_cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['complete_cart'] as $index => $item): ?>
            <div class="cart-item">
                <img src="/images/<?= htmlspecialchars($item['image']) ?>" alt="Product Image" onerror="this.onerror=null;this.src='/images/default.png';">
                <strong><?= htmlspecialchars($item['name']) ?></strong>
                <small><?= htmlspecialchars($item['size']) ?> - Ksh <?= number_format($item['price']) ?></small>
                <form method="POST" style="margin-top:5px;">
                    <input type="hidden" name="remove_index" value="<?= $index ?>">
                    <button type="submit" class="btn-remove">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
        <hr>
        <strong>Total: Ksh <?= number_format(array_sum(array_column($_SESSION['complete_cart'], 'price'))) ?></strong>
        <br><br>
        <a href="select_location.php?type=complete" class="btn-order">Proceed to Order</a>
    <?php endif; ?>
</div>

<!-- Main Product Display -->
<div class="container">
    <h2>Order Complete Gas Cylinder</h2>

    <?php if ($success_msg): ?>
        <div class="success"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <div class="products">
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <div class="product">
                <img src="/images/<?= htmlspecialchars($row['image'] ?: 'default.png') ?>" alt="<?= htmlspecialchars($row['name']) ?>" onerror="this.onerror=null;this.src='/images/default.png';">
                <h4><?= htmlspecialchars($row['name']) ?></h4>
                <p>6kg - Ksh <?= number_format($row['price_6kg']) ?></p>
                <p>13kg - Ksh <?= number_format($row['price_13kg']) ?></p>
                <form action="" method="POST">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <select name="size" required>
                        <option value="">Select Size</option>
                        <option value="6kg">6kg</option>
                        <option value="13kg">13kg</option>
                    </select>
                    <button type="submit" name="add_complete_gas" class="btn">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>

        <!-- Custom Gas Entry Form -->
        <div class="product custom">
            <h4>Can't find your gas?</h4>
            <p>Enter the name and size</p>
            <form method="POST">
                <input type="hidden" name="add_custom_gas" value="1">
                <input type="text" name="custom_name" placeholder="Type gas name..." required>
                <select name="custom_size" required>
                    <option value="">Select Size</option>
                    <option value="6kg">6kg - Ksh 3,700</option>
                    <option value="13kg">13kg - Ksh 7,500</option>
                </select>
                <button type="submit" class="btn">Add to cart</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
