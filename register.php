<?php
session_start();
require 'db_config.php'; // Use external DB config

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $full_name = filter_var(trim($_POST['full_name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $mobile = filter_var(trim($_POST['mobile']), FILTER_SANITIZE_STRING);
    $qualification = filter_var(trim($_POST['qualification']), FILTER_SANITIZE_STRING);
    $grad_year = (int) $_POST['grad_year'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $_SESSION['error'] = "Error: Email already registered!";
        header("Location: register.php");
        exit();
    } else {
        // Insert new student record
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, mobile, qualification, grad_year, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $full_name, $email, $mobile, $qualification, $grad_year, $password);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please log in.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: Could not register. Try again.";
            header("Location: register.php");
            exit();
        }
        $stmt->close();
    }
    $check_email->close();
}
$conn->close();
?>
