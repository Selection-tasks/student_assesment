<?php
// Database configuration
$host = "localhost";  
$username = "root";   
$password = "";       
$database = "student_assessment"; 

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create database connection
    $conn = new mysqli($host, $username, $password, $database);
    $conn->set_charset("utf8mb4"); // Ensure proper character encoding
} catch (Exception $e) {
    exit(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}
?>
