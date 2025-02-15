<?php
session_start();

// Optional: Check if a student is logged in
if (!isset($_SESSION['student_id'])) {
    // For testing purposes, you can uncomment the next line to simulate a logged-in student:
    // $_SESSION['student_id'] = 1;
    // Or redirect to login page:
    // header("Location: login.php");
    // exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Page</title>
</head>
<body>
    <h1>Quiz Page Test</h1>
    <p>If you can see this, your quiz.php page is working!</p>
</body>
</html>
