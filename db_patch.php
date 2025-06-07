<?php
$mysqli = new mysqli(
    'shortline.proxy.rlwy.net', // host
    'root',                     // user
    'kcvXfefWZmWlTSwRZWmOkHCakVdwTAiu',  // password
    'railway',                  // database
    45164                       // correct Railway port
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Run patch only if the columns don't already exist
$checkColumns = $mysqli->query("SHOW COLUMNS FROM orders LIKE 'county'");
if ($checkColumns->num_rows == 0) {
    $sql = "ALTER TABLE orders
            ADD COLUMN county VARCHAR(255) DEFAULT '',
            ADD COLUMN town VARCHAR(255) DEFAULT '',
            ADD COLUMN additional_notes TEXT DEFAULT ''";

    if ($mysqli->query($sql) === TRUE) {
        echo "✅ Table updated successfully.";
    } else {
        echo "❌ Error updating table: " . $mysqli->error;
    }
} else {
    echo "ℹ️ Columns already exist. No changes made.";
}

$mysqli->close();
?>
