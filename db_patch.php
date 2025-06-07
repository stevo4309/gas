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

$column = 'delivery_address';
$table = 'orders';

// Check if column exists
$result = $mysqli->query("SHOW COLUMNS FROM $table LIKE '$column'");
if ($result->num_rows === 0) {
    $sql = "ALTER TABLE $table ADD COLUMN $column TEXT";
    if ($mysqli->query($sql) === TRUE) {
        echo "Column '$column' added successfully.<br>";
    } else {
        echo "Error updating table: " . $mysqli->error . "<br>";
    }
} else {
    echo "Column '$column' already exists, skipping.<br>";
}

$mysqli->close();
?>
