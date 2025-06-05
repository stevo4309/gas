<?php
session_start();
include('db_connection.php');

// Initialize refill cart
if (!isset($_SESSION['refill_cart'])) {
    $_SESSION['refill_cart'] = [];
}

$success_msg = null;

// Add to cart (listed products)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $size = $_POST['size'];

    $sql = "SELECT * FROM gas_products WHERE id = $product_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        $price = $size === '6kg' ? $product['price_6kg'] : $product['price_13kg'];
        $item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'size' => $size,
            'price' => $price,
            'image' => $product['image']
        ];
        $_SESSION['refill_cart'][] = $item;
        $_SESSION['success_msg'] = "Added to cart!";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Add custom gas not listed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_custom_gas'])) {
    $custom_name = trim($_POST['custom_gas_name']);
    $size = $_POST['size'];

    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM gas_products LIMIT 1"));
    if ($product && in_array($size, ['6kg', '13kg'])) {
        $price = $size === '6kg' ? $product['price_6kg'] : $product['price_13kg'];

        $item = [
            'id' => 0,
            'name' => $custom_name,
            'size' => $size,
            'price' => $price,
            'image' => 'default.png'
        ];
        $_SESSION['refill_cart'][] = $item;
        $_SESSION['success_msg'] = "Custom gas added to cart!";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_index'])) {
    $index = (int)$_POST['remove_index'];
    if (isset($_SESSION['refill_cart'][$index])) {
        unset($_SESSION['refill_cart'][$index]);
        $_SESSION['refill_cart'] = array_values($_SESSION['refill_cart']); // reindex
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Retrieve success message from session
if (isset($_SESSION['success_msg'])) {
    $success_msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM gas_products");
if (!$products) {
    die("Error fetching products: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Refill Gas - Joy Smart Gas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
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
            margin-bottom: 10px;
        }

        .product h4 {
            margin: 10px 0;
            color: #007bff;
        }

        .product select,
        .product button,
        .product input[type="text"] {
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
            font-size: 12px;
            cursor: pointer;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
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
        }

        .btn-order:hover {
            background-color: #e0a800;
            color: white;
        }

       @media (max-width: 768px) {
    body {
        padding-right: 0;
    }

    .cart-sidebar {
        position: static;
        width: 100%;
        height: auto;
        padding: 15px;
    }

    .container {
        margin: 10px;
        padding: 20px;
    }

    /* Change here: 2 columns on mobile */
    .products {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .product img {
        width: 80px;
    }

    .btn, .btn-order, .btn-remove {
        font-size: 14px;
        padding: 10px;
    }

    h2 {
        font-size: 22px;
    }

    .cart-sidebar h3 {
        font-size: 20px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .cart-item img {
        width: 30px;
        margin-right: 10px;
    }

    .cart-item small {
        font-size: 13px;
    }

    .cart-item form {
        margin-top: 5px;
        width: 100%;
        text-align: right;
    }
}

    </style>
</head>
<body>

<div class="cart-sidebar">
    <h3>My Cart</h3>
    <?php if (empty($_SESSION['refill_cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['refill_cart'] as $index => $item): ?>
            <div class="cart-item">
                <img src="images/<?= htmlspecialchars($item['image'] ?? 'default.png') ?>" alt="Product">
                <strong><?= htmlspecialchars($item['name'] ?? 'Unknown') ?></strong>
                <small><?= htmlspecialchars($item['size']) ?> - Ksh <?= number_format($item['price']) ?></small>
                <form method="POST" style="margin-top:5px;">
                    <input type="hidden" name="remove_index" value="<?= $index ?>">
                    <button type="submit" class="btn-remove">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
        <hr>
        <strong>Total: Ksh 
            <?= number_format(array_sum(array_column($_SESSION['refill_cart'], 'price'))) ?>
        </strong>
        <br><br>
        <a href="order_details.php?type=refill" class="btn btn-order">Proceed to Order</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2>Choose Gas Refill</h2>

    <?php if (!empty($success_msg)): ?>
        <div class="success"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <div class="products">
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <div class="product">
                <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
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
                    <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>

        <!-- Custom Gas Entry -->
        <div class="product" style="border: 2px dashed #aaa; margin-top: 30px;">
            <h4>Can't find your gas here?</h4>
            <form method="POST">
                <input type="hidden" name="add_custom_gas" value="1">
                <input type="text" name="custom_gas_name" placeholder="Type your gas name here" required>
                <select name="size" required>
                    <option value="">Select Size</option>
                    <option value="6kg">6kg</option>
                    <option value="13kg">13kg</option>
                </select>
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
