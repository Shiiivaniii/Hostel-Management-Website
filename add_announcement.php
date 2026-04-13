<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

include 'db.php';

$title = $_POST['title'];
$subtitle = $_POST['subtitle'];   // ✅ FIXED
$body = $_POST['body'];

$attachmentPath = NULL;

if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {

    $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
    $fileName = $_FILES['attachment']['name'];
    $fileTmp = $_FILES['attachment']['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedTypes)) {

        $newFileName = time() . "_" . basename($fileName);
        $uploadDir = "uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $uploadPath)) {
            $attachmentPath = $uploadPath;
        }
    }
}

$stmt = $conn->prepare("INSERT INTO announcements (title, subtitle, body, attachment) VALUES (?, ?, ?, ?)"); // ✅ FIXED
$stmt->bind_param("ssss", $title, $subtitle, $body, $attachmentPath);

if ($stmt->execute()) {
    header("Location: hostel.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>