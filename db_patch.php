<?php
$mysqli = new mysqli(
    'shortline.proxy.rlwy.net', // host
    'root',                     // user
    'kcvXfefWZmWlTSwRZWmOkHCakVdwTAiu',  // password
    'railway',                  // database
    45164                       // port (make sure it's correct from Railway)
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Add missing columns
$sql = "ALTER TABLE orders
        ADD COLUMN IF NOT EXISTS delivery_address TEXT;";

if ($mysqli->query($sql) === TRUE) {
    echo "Columns added successfully.";
} else {
    echo "Error updating table: " . $mysqli->error;
}

$mysqli->close();
?>
