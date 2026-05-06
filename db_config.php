<?php

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "food_db";

// Establish connection
$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set character set
mysqli_set_charset($conn, "utf8");

?>