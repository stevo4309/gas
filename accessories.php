<?php
session_start();
include('db_connection.php');

// Initialize accessories cart
if (!isset($_SESSION['accessories_cart'])) {
    $_SESSION['accessories_cart'] = [];
}

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $stmt = $conn->prepare("SELECT * FROM accessories WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $_SESSION['accessories_cart'][] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'image' => $item['image']
        ];
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_index'])) {
    $index = (int)$_POST['remove_index'];
    if (isset($_SESSION['accessories_cart'][$index])) {
        unset($_SESSION['accessories_cart'][$index]);
        $_SESSION['accessories_cart'] = array_values($_SESSION['accessories_cart']);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch accessories
$sql = "SELECT * FROM accessories";
$result = mysqli_query($conn, $sql);
$accessories = mysqli_num_rows($result) > 0 ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accessories - Joy Smart Gas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding-right: 280px;
        }

        .cart-toggle {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            background: #f68b1e;
            color: #fff;
            padding: 10px 15px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            z-index: 101;
            cursor: pointer;
        }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 260px;
            height: 100%;
            background: #fff;
            border-left: 1px solid #ddd;
            padding: 20px;
            overflow-y: auto;
            box-shadow: -2px 0 6px rgba(0, 0, 0, 0.05);
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .cart-item img {
            width: 50px;
            margin-right: 10px;
            vertical-align: middle;
            border-radius: 6px;
        }

        .cart-item strong {
            display: block;
            font-size: 15px;
            color: #333;
        }

        .cart-item small {
            color: #777;
        }

        .btn-remove {
            background-color: #ff4d4f;
            color: white;
            padding: 5px 10px;
            border: none;
            font-size: 12px;
            border-radius: 4px;
            margin-top: 5px;
            cursor: pointer;
        }

        .btn-order {
            background-color: #f68b1e;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .accessory {
            background: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .accessory img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .accessory h3 {
            font-size: 16px;
            color: #333;
        }

        .accessory .price {
            font-size: 15px;
            color: #f68b1e;
            font-weight: bold;
        }

        .btn {
            margin-top: 10px;
            background-color: #3b5998;
            color: white;
            padding: 8px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-weight: bold;
            cursor: pointer;
        }

        .get-in-touch {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
        }

        .get-in-touch a {
            color: #f68b1e;
            font-weight: bold;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            body {
                padding-right: 0;
            }

            .cart-sidebar {
                transform: translateX(100%);
                position: fixed;
                top: 0;
                width: 100%;
                height: auto;
                border-left: none;
                border-top: 3px solid #f68b1e;
            }

            .cart-sidebar.show {
                transform: translateX(0);
            }

            .cart-toggle {
                display: block;
            }

            .btn-order {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Toggle Button for Cart on Mobile -->
<button class="cart-toggle" onclick="toggleCart()">Cart</button>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
    <h3>Accessories Cart</h3>
    <?php if (empty($_SESSION['accessories_cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($_SESSION['accessories_cart'] as $index => $item): ?>
            <div class="cart-item">
                <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="Product">
                <strong><?= htmlspecialchars($item['name']) ?></strong>
                <small>Ksh <?= number_format($item['price']) ?></small>
                <form method="POST" style="margin-top:5px;">
                    <input type="hidden" name="remove_index" value="<?= $index ?>">
                    <button type="submit" class="btn-remove">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
        <hr>
        <strong>Total: Ksh <?= number_format(array_sum(array_column($_SESSION['accessories_cart'], 'price'))) ?></strong>
        <br><br>
        <a href="order_details.php?type=accessories" class="btn-order">Proceed to Order</a>
    <?php endif; ?>
</div>

<!-- Accessories Display -->
<div class="container">
    <h2>Cooking Gas Accessories</h2>
    <div class="products">
        <?php if (!empty($accessories)): ?>
            <?php foreach ($accessories as $item): ?>
                <div class="accessory">
                    <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="price">Ksh <?= number_format($item['price']) ?></p>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No accessories found.</p>
        <?php endif; ?>
    </div>

    <div class="get-in-touch">
        If you can't find the item you want, kindly <a href="contact.php">get in touch</a>.
    </div>
</div>

<script>
    function toggleCart() {
        const sidebar = document.getElementById('cartSidebar');
        sidebar.classList.toggle('show');
    }
</script>

</body>
</html>
