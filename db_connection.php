<?php
$host = 'containers-abcxyz.up.railway.app'; // Replace this with the actual value of RAILWAY_PRIVATE_DOMAIN
$port = 3306;
$username = 'root';
$password = 'kcvXfefWZmWlTSwRZWmOkHCakVdwTAiu';
$database = 'railway';

$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
