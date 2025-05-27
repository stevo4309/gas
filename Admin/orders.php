<?php
// orders.php

include('../db_connection.php'); // Adjust if your DB connection file is in a different folder
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders | Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-left: 270px; /* to avoid overlap with sidebar */
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2d3e50;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status {
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
        }

        .status.pending { background-color: orange; }
        .status.completed { background-color: green; }
        .status.cancelled { background-color: red; }
    </style>
</head>
<body>

    <h1>Customer Orders</h1>

    <table>
        <thead>
            <tr>
                <th>#Order ID</th>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM orders ORDER BY order_date DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    $statusClass = strtolower($row['status']);
                    ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                        <td><?= htmlspecialchars($row['product']); ?></td>
                        <td><?= $row['quantity']; ?></td>
                        <td><?= $row['payment_method']; ?></td>
                        <td><span class="status <?= $statusClass; ?>"><?= ucfirst($statusClass); ?></span></td>
                        <td><?= $row['order_date']; ?></td>
                    </tr>
                    <?php
                endwhile;
            else:
                echo "<tr><td colspan='7'>No orders found.</td></tr>";
            endif;
            ?>
        </tbody>
    </table>

</body>
</html>
