<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "student_assessment";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Use prepared statements to prevent SQL injection
$sql = "SELECT students.id, students.full_name, students.email, students.qualification, students.grad_year, 
               COALESCE(quiz_results.score, NULL) AS score
        FROM students 
        LEFT JOIN quiz_results ON students.id = quiz_results.student_id";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
$students = [];

// Fetch data safely
while ($row = $result->fetch_assoc()) {
    $students[] = [
        "id" => (int) $row["id"],
        "full_name" => htmlspecialchars($row["full_name"]),
        "email" => htmlspecialchars($row["email"]),
        "qualification" => htmlspecialchars($row["qualification"]),
        "grad_year" => (int) $row["grad_year"],
        "score" => $row["score"] !== null ? (int) $row["score"] : "N/A"
    ];
}

// Close connections
$stmt->close();
$conn->close();

// Return JSON response
echo json_encode($students);
?>
