<?php
$mysqli = new mysqli(
    'shortline.proxy.rlwy.net', // host
    'root',                     // user
    'kcvXfefWZmWlTSwRZWmOkHCakVdwTAiu',  // password
    'railway',                  // database
    45164                       // ✅ correct port from Railway
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "ALTER TABLE orders
        ADD COLUMN county VARCHAR(255) DEFAULT '',
        ADD COLUMN town VARCHAR(255) DEFAULT '',
        ADD COLUMN additional_notes TEXT;"; // ❌ removed DEFAULT from TEXT

if ($mysqli->query($sql) === TRUE) {
    echo "Table updated successfully.";
} else {
    echo "Error updating table: " . $mysqli->error;
}

$mysqli->close();
?>
