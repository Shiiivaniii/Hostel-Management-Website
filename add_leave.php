<?php
session_start();

/* Allow ONLY students */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include 'db.php';

    // Get POST values safely
    $student_name = $_POST['student_name'] ?? '';
    $room_number  = $_POST['room_number'] ?? '';
    $from_date    = $_POST['from_date'] ?? '';
    $to_date      = $_POST['to_date'] ?? '';
    $reason       = $_POST['reason'] ?? '';
    $contact      = $_POST['contact_number'] ?? '';

    // Basic validation
    if (!$student_name || !$room_number || !$from_date || !$to_date || !$reason || !$contact) {
        die("All fields are required!");
    }

    // Prepare statement using correct table
    $stmt = $conn->prepare("INSERT INTO leaves 
        (student_name, room_number, from_date, to_date, reason, contact_number) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $student_name, $room_number, $from_date, $to_date, $reason, $contact);

    if ($stmt->execute()) {
        echo "<script>alert('Leave submitted successfully!'); window.location.href='leave.php';</script>";
    } else {
        echo "Database error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    // Redirect if accessed directly
    header("Location: leave.php");
    exit();
}
?>