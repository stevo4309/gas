<?php
$mysqli = new mysqli(
    'shortline.proxy.rlwy.net',
    'root',
    'kcvXfefWZmWlTSwRZWmOkHCakVdwTAiu',
    'railway',
    45164
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "ALTER TABLE orders MODIFY COLUMN apartment_name VARCHAR(255) NULL DEFAULT NULL";

if ($mysqli->query($sql) === TRUE) {
    echo "Column 'apartment_name' modified successfully.<br>";
} else {
    echo "Error modifying column: " . $mysqli->error . "<br>";
}

$mysqli->close();
?>
