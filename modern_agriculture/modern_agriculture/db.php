<?php
$servername = "localhost";
$username = "root";
$password = "";  // default in XAMPP
$dbname = "modernagriculture"; // must match the name in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
