<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "joy";

// Create connection using MySQLi with improved error handling
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and display detailed error if connection fails
if ($conn->connect_error) {
    // Log the error message for debugging
    error_log("Connection failed: " . $conn->connect_error);

    // Display a user-friendly error message
    die("Database connection failed. Please try again later.");
}

// Set the charset to UTF-8 for better handling of special characters
$conn->set_charset("utf8");

?>
