<?php
session_start();
require 'db_config.php';  // External database configuration file

// Handle Registration
if (isset($_POST['register'])) {
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mobile_number = filter_var($_POST['mobile_number'], FILTER_SANITIZE_STRING);
    $qualification = filter_var($_POST['qualification'], FILTER_SANITIZE_STRING);
    $grad_year = (int) $_POST['grad_year'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Error: Email already exists!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, mobile, qualification, grad_year, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $full_name, $email, $mobile_number, $qualification, $grad_year, $password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please log in.');</script>";
        } else {
            echo "<script>alert('Registration failed! Try again.');</script>";
        }
    }
    $stmt->close();
}

// Handle Login
if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, email, password FROM students WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['email'] = $student['email'];
        $_SESSION['full_name'] = $student['full_name'];
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
    $stmt->close();
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Assessment</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 40px; }
        form { margin: 20px auto; width: 300px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 8px; background: blue; color: white; border: none; cursor: pointer; }
        .container { max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
    </style>
</head>
<body>

<div class="container">
    <?php if (!isset($_SESSION['email'])): ?>
        <!-- Registration & Login Forms -->
        <h2>Student Assessment System</h2>

        <!-- Registration Form -->
        <h3>Register</h3>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="mobile_number" placeholder="Mobile Number" required>
            <input type="text" name="qualification" placeholder="Qualification" required>
            <input type="number" name="grad_year" placeholder="Graduation Year" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>

        <!-- Login Form -->
        <h3>Login</h3>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

    <?php else: ?>
        <!-- Student Dashboard -->
        <?php
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("SELECT * FROM students WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        ?>

        <h2>Welcome, <?= htmlspecialchars($student['full_name']) ?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        <p><strong>Qualification:</strong> <?= htmlspecialchars($student['qualification']) ?></p>
        <p><strong>Graduation Year:</strong> <?= htmlspecialchars($student['grad_year']) ?></p>

        <a href="index.php?logout=true"><button>Logout</button></a>

    <?php endif; ?>
</div>

</body>
</html>
