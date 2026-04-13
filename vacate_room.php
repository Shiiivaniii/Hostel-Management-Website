<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $room_number = intval($_POST['room_number']);
    $block = $_POST['block'];
    $floor = intval($_POST['floor']);

    $stmt = $conn->prepare("UPDATE rooms 
                            SET student_id = NULL, status = 'Vacant'
                            WHERE room_number = ? AND block = ? AND floor = ?");

    $stmt->bind_param("isi", $room_number, $block, $floor);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "success";
    } else {
        echo "error";
    }
}
?>