<?php 
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "complaint_management_system"; 

$conn = mysqli_connect($host, $username, $password, $database); 

if (!$conn) { 
    die("Database Connection Failed: " . mysqli_connect_error()); 
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Make $conn available globally
$GLOBALS['conn'] = $conn;
?>