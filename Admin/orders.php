<?php
// orders.php
include('../db_connection.php');

// Handle AJAX request to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $allowedStatuses = ['Pending', 'Completed', 'Cancelled'];

    if (in_array($status, $allowedStatuses)) {
        $update = mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$orderId");
        if ($update) {
            echo "Status updated";
        } else {
            http_response_code(500);
            echo "Database error";
        }
    } else {
        http_response_code(400);
        echo "Invalid status";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orders | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 20px 30px;
      margin-left: 270px;
    }
    h1 {
      color: #333;
      margin-bottom: 25px;
    }
    .orders-wrapper {
      background: #fff;
      border-radius: 12px;
      overflow-x: auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 900px;
    }
    th, td {
      padding: 14px 18px;
      text-align: left;
      border-bottom: 1px solid #eaeaea;
    }
    th {
      background-color: #2d3e50;
      color: #fff;
      font-weight: 600;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .status {
      padding: 6px 12px;
      border-radius: 30px;
      font-weight: 600;
      font-size: 13px;
      display: inline-block;
      text-transform: capitalize;
    }
    .status.pending { background-color: #ff9800; color: #fff; }
    .status.completed { background-color: #4caf50; color: #fff; }
    .status.cancelled { background-color: #f44336; color: #fff; }
    select {
      padding: 5px 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }
    @media (max-width: 768px) {
      body {
        margin-left: 0;
        padding: 10px;
      }
      .orders-wrapper {
        padding: 10px;
      }
      table {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<h1>Customer Orders</h1>

<div class="orders-wrapper">
  <table>
    <thead>
      <tr>
        <th>#Order ID</th>
        <th>Customer Name</th>
        <th>Ordered Items</th>
        <th>Quantity</th>
        <th>Payment Method</th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Update Status</th>
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
          <tr id="order-<?= $row['id']; ?>">
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['customer_name']); ?></td>
            <td><?= nl2br(htmlspecialchars($row['product'])); ?></td> <!-- This shows actual items ordered -->
            <td><?= (int)$row['quantity']; ?></td>
            <td><?= htmlspecialchars($row['payment_method']); ?></td>
            <td><span class="status <?= $statusClass; ?>" id="status-label-<?= $row['id']; ?>"><?= ucfirst($statusClass); ?></span></td>
            <td><?= htmlspecialchars($row['order_date']); ?></td>
            <td>
              <select onchange="updateStatus(<?= $row['id']; ?>, this.value)">
                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
              </select>
            </td>
          </tr>
        <?php
        endwhile;
      else:
        echo "<tr><td colspan='8'>No orders found.</td></tr>";
      endif;
      ?>
    </tbody>
  </table>
</div>

<script>
function updateStatus(orderId, newStatus) {
  fetch('orders.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `order_id=${orderId}&status=${encodeURIComponent(newStatus)}`
  })
  .then(response => {
    if (!response.ok) throw new Error("Failed to update");
    return response.text();
  })
  .then(data => {
    const label = document.getElementById(`status-label-${orderId}`);
    label.textContent = newStatus;
    label.className = 'status ' + newStatus.toLowerCase();
  })
  .catch(error => alert("Error updating status: " + error.message));
}
</script>

</body>
</html>
