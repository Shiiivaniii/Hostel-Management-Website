<?php
session_start();

include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc(); // only fetch once

        if ($user['password'] === $password) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Send everyone to hostel.php
            header("Location: hostel.php");
            exit();

        } else {
            echo "Wrong password.";
        }

    } else {
        echo "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Back Button -->
<button onclick="history.back()" style="padding: 10px 20px; background-color:#4CAF50; color:white; border:none; border-radius:5px; cursor:pointer;">
    Back
</button>
</body>
</html>