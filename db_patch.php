<?php
$mysqli = new mysqli(
    'shortline.proxy.rlwy.net', // host
    'root',                     // user
    'kcvXfefWZmWlTSwRZWmOkHCakVdwTAiu',  // password
    'railway',                  // database
    45164                       // port
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "ALTER TABLE orders
        ADD COLUMN subcounty VARCHAR(255) DEFAULT '';";

if ($mysqli->query($sql) === TRUE) {
    echo "Subcounty column added successfully.";
} else {
    echo "Error adding column: " . $mysqli->error;
}

$mysqli->close();
?>
