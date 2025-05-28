<?php
// Include database connection
include('db_connection.php');

// Fetch only complete gas cylinders from the new table, including prices
$sql = "SELECT * FROM complete_gas_products";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Gas Cylinder - Joy Smart Gas</title>
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

        select {
            padding: 8px;
            font-size: 14px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .price-display {
            font-weight: bold;
            color: #28a745;
            font-size: 16px;
        }

        .order-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .order-btn:hover {
            background-color: #0056b3;
        }

        .brand-tooltip {
            position: relative;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Complete Gas Cylinder</h2>

    <table>
        <tr>
            <th>Image</th>
            <th>Brand</th>
            <th>Size</th>
            <th>Price (Ksh)</th>
            <th>Order</th>
        </tr>

        <?php while ($brand = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td class="brand-tooltip"><img src="images/<?= $brand['image'] ?>" alt="<?= htmlspecialchars($brand['name']) ?>"></td>
                <td><?= htmlspecialchars($brand['name']) ?></td>
                <td>
                    <select class="size-selector" data-brand="<?= htmlspecialchars($brand['name']) ?>">
                        <option value="6kg" data-price="<?= $brand['price_6kg'] ?>">6kg</option>
                        <option value="13kg" data-price="<?= $brand['price_13kg'] ?>">13kg</option>
                    </select>
                </td>
                <td class="price-display"><?= number_format($brand['price_6kg']) ?></td>
                <td>
                    <a href="order_details.php?product=complete_gas&brand=<?= urlencode($brand['name']) ?>&size=6kg&price=<?= $brand['price_6kg'] ?>" 
                        class="order-btn order-link" 
                        data-brand="<?= htmlspecialchars($brand['name']) ?>">
                        Order Now
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    document.querySelectorAll('.size-selector').forEach(select => {
        select.addEventListener('change', function() {
            let row = this.closest('tr');
            let price = this.options[this.selectedIndex].getAttribute('data-price');
            let brand = this.getAttribute('data-brand');
            
            row.querySelector('.price-display').innerText = new Intl.NumberFormat().format(price);
            
            let orderLink = row.querySelector('.order-link');
            orderLink.href = order_details.php?product=complete_gas&brand=${encodeURIComponent(brand)}&size=${encodeURIComponent(this.value)}&price=${encodeURIComponent(price)};
        });
    });
</script>

</body>
</html>