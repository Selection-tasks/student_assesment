<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    die(json_encode(["error" => "Unauthorized access. Please log in."]));
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_assessment";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Function to sanitize input
function sanitize($data, $conn) {
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

// Fetch form data
$full_name = sanitize($_POST['full_name'], $conn);
$email = sanitize($_POST['email'], $conn);
$mobile = sanitize($_POST['mobile'], $conn);
$qualification = sanitize($_POST['qualification'], $conn);
$grad_year = sanitize($_POST['grad_year'], $conn);
$about = sanitize($_POST['about'], $conn);
$certifications = sanitize($_POST['certifications'], $conn);
$projects = sanitize($_POST['projects'], $conn);
$skills = sanitize($_POST['skills'], $conn);
$software = sanitize($_POST['software'], $conn);
$experience = sanitize($_POST['experience'], $conn);
$soft_skills = sanitize($_POST['soft_skills'], $conn);

// Handle File Upload Securely
$resume = "";
if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $allowed_extensions = ["pdf", "doc", "docx"];
    $file_ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
    $file_size = $_FILES['resume']['size'];
    $max_size = 2 * 1024 * 1024; // 2MB limit

    if (in_array($file_ext, $allowed_extensions) && $file_size <= $max_size) {
        $resume = "uploads/" . time() . "_" . basename($_FILES['resume']['name']);
        if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resume)) {
            die(json_encode(["error" => "File upload failed."]));
        }
    } else {
        die(json_encode(["error" => "Invalid file type or size exceeded (Max 2MB)."]));
    }
}

// Use Prepared Statement for SQL Query
$sql = "INSERT INTO students (full_name, email, mobile, qualification, grad_year, about, certifications, projects, skills, software, resume, experience, soft_skills) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssisssssssi", $full_name, $email, $mobile, $qualification, $grad_year, $about, $certifications, $projects, $skills, $software, $resume, $experience, $soft_skills);

if ($stmt->execute()) {
    echo json_encode(["success" => "Submission successful!", "redirect" => "quiz.html"]);
} else {
    echo json_encode(["error" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
