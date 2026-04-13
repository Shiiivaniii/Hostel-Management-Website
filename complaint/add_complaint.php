<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['role']) || !isset($_SESSION['hostel'])) {
    header("Location: ../signin.php");
    exit();
}

// Show errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL credentials (same for all hostel databases)
$host = "localhost";
$user = "root";
$password = "";
$database = $_SESSION['hostel']; // dynamically connect to the user's hostel DB

// Create database connection
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$studentName = $_POST['studentName'] ?? '';
$roomNumber = $_POST['roomNumber'] ?? '';
$hostel = $_POST['hostel'] ?? $_SESSION['hostel']; // optional fallback
$block = $_POST['block'] ?? '';
$email = $_POST['email'] ?? '';
$category = $_POST['category'] ?? '';
$priority = $_POST['priority'] ?? '';
$details = $_POST['details'] ?? '';

// Combine hostel and block for storage
$hostel_block = $hostel . " - " . $block;

// Prepare and execute insert
$sql = "INSERT INTO complaints 
(student_name, room_number, hostel_block, email, category, priority, details) 
VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "sssssss",
    $studentName,
    $roomNumber,
    $hostel_block,
    $email,
    $category,
    $priority,
    $details
);

if ($stmt->execute()) {
    $id = $stmt->insert_id;
    echo "<script>
            alert('Complaint Submitted! ID: #$id');
            window.location.href='index.php';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>