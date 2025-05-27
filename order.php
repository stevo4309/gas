<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product"])) {
    $product = $_POST["product"];

    // Redirect based on the selected product type
    if ($product == "refilling") {
        header("Location: refilling.php");
        exit();
    } elseif ($product == "complete_gas") {
        header("Location: complete_gas.php");
        exit();
    } elseif ($product == "accessories") {
        header("Location: accessories.php");
        exit();
    } else {
        echo "<h2 style='color: red; text-align: center;'>Invalid Product Selection</h2>";
    }
} else {
    echo "<h2 style='color: red; text-align: center;'>No Order Data Received</h2>";
}
?>
