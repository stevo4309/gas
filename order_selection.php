<?php
$product = $_GET["product"] ?? "";
$brand = $_GET["brand"] ?? "";

// Gas sizes & prices
$gasPrices = [
    "6kg" => 1200,
    "13kg" => 2500
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Gas Type & Quantity</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; text-align: center; padding: 50px; }
        .container { max-width: 400px; margin: auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        input, select, button { width: 100%; padding: 10px; margin: 10px 0; }
        button { background-color: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Select Gas Details</h2>
        <form method="POST" action="order_form.php">
            <input type="hidden" name="product" value="<?= htmlspecialchars($product) ?>">
            <input type="hidden" name="brand" value="<?= htmlspecialchars($brand) ?>">

            <label for="size">Gas Size:</label>
            <select id="size" name="size" required>
                <?php foreach ($gasPrices as $size => $price): ?>
                    <option value="<?= $size ?>" data-price="<?= $price ?>">
                        <?= $size ?> kg - Ksh <?= number_format($price) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" required>

            <input type="hidden" id="price" name="price" value="">

            <button type="submit">Next</button>
        </form>
    </div>

    <script>
        document.getElementById("size").addEventListener("change", function() {
            let price = this.options[this.selectedIndex].getAttribute("data-price");
            document.getElementById("price").value = price;
        });

        document.getElementById("size").dispatchEvent(new Event("change"));
    </script>

</body>
</html>
