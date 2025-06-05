<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['complete_cart'])) {
    $_SESSION['complete_cart'] = [];
}

$redirect = false;

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
        $item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'size' => $size,
            'price' => $price,
            'image' => $product['image']
        ];
        $_SESSION['complete_cart'][] = $item;
        $_SESSION['success_msg'] = "Complete gas added to cart!";
    }
    $stmt->close();
    $redirect = true;
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_index'])) {
    $index = (int)$_POST['remove_index'];
    if (isset($_SESSION['complete_cart'][$index])) {
        unset($_SESSION['complete_cart'][$index]);
        $_SESSION['complete_cart'] = array_values($_SESSION['complete_cart']);
    }
    $redirect = true;
}

if ($redirect) {
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$success_msg = '';
if (isset($_SESSION['success_msg'])) {
    $success_msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .product {
            border: 1px solid #ddd;
            background: #fafafa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }

        .product img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .product h4 {
            color: #007bff;
        }

        .product select, .product input, .product button {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .btn {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-remove {
            background: #dc3545;
            color: white;
            border: none;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 5px;
        }

        .btn-order {
            background: #ffc107;
            color: #333;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
            border-radius: 6px;
            margin-top: 10px;
            font-weight: 600;
        }

        .btn-order:hover {
            background: #e0a800;
            color: white;
        }

        .success {
            text-align: center;
            color: green;
            margin-bottom: 15px;
        }

        .product.custom {
            border: 2px dashed #aaa;
            background-color: #fff7e6;
        }

        .cart-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: 260px;
            height: 100%;
            background: #343a40;
            color: white;
            padding: 20px;
            overflow-y: auto;
            z-index: 1000;
        }

        .cart-sidebar h3 {
            color: #ffc107;
            text-align: center;
        }

        .cart-item {
            margin-bottom: 15px;
            border-bottom: 1px solid #555;
            padding-bottom: 10px;
        }

        .cart-item img {
            width: 40px;
            vertical-align: middle;
            margin-right: 10px;
        }

        .cart-item small {
            color: #ccc;
            display: block;
        }

        .cart-toggle {
            display: none;
            background: #343a40;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            position: fixed;
            right: 10px;
            top: 10px;
            z-index: 1100;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            body {
                padding-right: 0;
            }

            .cart-sidebar {
                width: 100%;
                height: auto;
                position: static;
                margin-top: 20px;
            }

            .container {
                padding: 20px;
                margin: 20px 10px;
            }

            .cart-toggle {
                display: block;
            }

            .cart-sidebar.hidden {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- Toggle Cart for Mobile -->
<button class="cart-toggle" onclick="toggleCart()">Toggle Cart</button>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
    <h3>Complete Gas Cart</h3>
    <?php if (empty($_SESSION['complete_cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['complete_cart'] as $index => $item): ?>
            <div class="cart-item">
                <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="Product">
                <strong><?= htmlspecialchars($item['name']) ?></strong>
                <small><?= htmlspecialchars($item['size']) ?> - Ksh <?= number_format($item['price']) ?></small>
                <form method="POST">
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

<!-- Product Container -->
<div class="container">
    <h2>Order Complete Gas Cylinder</h2>
    <?php if ($success_msg): ?>
        <div class="success"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <div class="products">
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <div class="product">
                <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <h4><?= htmlspecialchars($row['name']) ?></h4>
                <p>6kg - Ksh <?= number_format($row['price_6kg']) ?></p>
                <p>13kg - Ksh <?= number_format($row['price_13kg']) ?></p>
                <form method="POST">
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

        <div class="product custom">
            <h4>Can't find your gas?</h4>
            <form method="POST">
                <input type="hidden" name="add_custom_gas" value="1">
                <input type="text" name="custom_name" placeholder="Gas brand..." required>
                <select name="custom_size" required>
                    <option value="">Select Size</option>
                    <option value="6kg">6kg - Ksh 4,500</option>
                    <option value="13kg">13kg - Ksh 8,500</option>
                </select>
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCart() {
    const sidebar = document.getElementById('cartSidebar');
    sidebar.classList.toggle('hidden');
}
</script>

</body>
</html>
