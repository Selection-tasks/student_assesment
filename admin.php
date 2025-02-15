<?php
session_start();
require 'db_config.php';  // External database configuration file

// Handle Admin Login
if (isset($_POST['admin_login'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
    } else {
        echo "<script>alert('Invalid credentials!');</script>";
    }
    $stmt->close();
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Assessment</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 40px; }
        form { margin: 20px auto; width: 300px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 8px; background: blue; color: white; border: none; cursor: pointer; }
        .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
<div class="container">
    <?php if (!isset($_SESSION['admin_id'])): ?>
        <!-- Admin Login Form -->
        <h2>Admin Login</h2>
        <form method="POST" action="admin.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="admin_login">Login</button>
        </form>
    <?php else: ?>
        <!-- Admin Dashboard -->
        <h2>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h2>
        <h3>Registered Students</h3>
        <?php
        // Fetch list of registered students
        $stmt = $conn->prepare("SELECT full_name, email, qualification, grad_year FROM students");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Qualification</th>
                        <th>Graduation Year</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['full_name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['qualification']) . "</td>
                        <td>" . htmlspecialchars($row['grad_year']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No students registered yet.</p>";
        }
        $stmt->close();
        ?>
        <br>
        <a href="admin.php?logout=true"><button>Logout</button></a>
    <?php endif; ?>
</div>
</body>
</html>
