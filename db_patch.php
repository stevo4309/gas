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

$queries = [
    "ALTER TABLE orders ADD COLUMN delivery_address TEXT"
];

foreach ($queries as $sql) {
    if ($mysqli->query($sql) === TRUE) {
        echo "Column added successfully.<br>";
    } else {
        // Ignore error if column already exists (error code 1060)
        if ($mysqli->errno == 1060) {
            echo "Column already exists, skipping.<br>";
        } else {
            echo "Error updating table: " . $mysqli->error . "<br>";
        }
    }
}

$mysqli->close();
?>
