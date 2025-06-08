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

// Modify the location_details column
$sql = "ALTER TABLE orders MODIFY COLUMN location_details TEXT NULL DEFAULT NULL";

if ($mysqli->query($sql) === TRUE) {
    echo "Column 'location_details' modified successfully.<br>";
} else {
    echo "Error modifying column: " . $mysqli->error . "<br>";
}

// Show full table details
$result = $mysqli->query("DESCRIBE orders");

if ($result) {
    echo "<h3>Current orders table structure:</h3>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error fetching table details: " . $mysqli->error;
}

$mysqli->close();
?>
