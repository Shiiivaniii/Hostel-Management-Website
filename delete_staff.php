<?php
session_start();

// Only allow admin to delete staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}

include "db.php";

// Check if staff ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_staff.php");
    exit();
}

$staff_id = intval($_GET['id']); // sanitize input

// Delete the staff member
$stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staff_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    // Redirect back to manage staff page
    header("Location: manage_staff.php");
    exit();
} else {
    echo "Error deleting staff: " . $stmt->error;
    $stmt->close();
    $conn->close();
}
?>