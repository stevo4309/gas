<?php
$host = 'mysql.railway.internal';
$port = 3306;
$username = 'root';
$password = 'kcvXfefWZmWlTSwRZWmOkHCakVdwTAiu'; // Your Railway password
$database = 'railway';

$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
