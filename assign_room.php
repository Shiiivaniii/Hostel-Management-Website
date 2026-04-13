<?php
session_start(); 
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = intval($_POST['student_id']);
    $room_number = intval($_POST['room_number']);
    $block = $_POST['block'];
    $floor = intval($_POST['floor']);

    // Check if room exists
    $check = $conn->prepare("SELECT student_id FROM rooms 
                             WHERE room_number = ? AND block = ? AND floor = ?");
    $check->bind_param("isi", $room_number, $block, $floor);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {
        echo "error";
        exit();
    }

    $row = $result->fetch_assoc();

    if (!empty($row['student_id'])) {
        echo "already";
        exit();
    }

    // Update room
    $stmt = $conn->prepare("UPDATE rooms 
                            SET student_id = ?, status = 'Allocated'
                            WHERE room_number = ? AND block = ? AND floor = ?");
    $stmt->bind_param("iisi", $student_id, $room_number, $block, $floor);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "success";
    } else {
        echo "error";
    }
}
?>