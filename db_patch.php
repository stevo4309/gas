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

$sql = "ALTER TABLE orders MODIFY COLUMN location_details TEXT NULL DEFAULT NULL";

if ($mysqli->query($sql) === TRUE) {
    echo "Column 'location_details' modified successfully.<br>";
} else {
    echo "Error modifying column: " . $mysqli->error . "<br>";
}

$mysqli->close();
?>
