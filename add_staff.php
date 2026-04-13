<?php
session_start();
include 'db.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $position = $_POST['position'];
    $hostel = $_POST['hostel'];

    $conn->query("INSERT INTO staff (name, email, contact, position, hostel_assigned) VALUES ('$name', '$email', '$contact', '$position', '$hostel')");
    header("Location: manage_staff.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
</head>
<body class="add-staff"> <!-- reuse edit-staff styling for consistency -->

<div class="page-wrapper">

        
        <div class="staff-card" style="max-width: 600px; margin: auto;">
        <h1>Add New Staff</h1>
        <form method="POST" class="leave-form">

            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="position" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact">
                </div>
            </div>

            <div class="form-group">
                <label>Hostel Assigned</label>
                <input type="text" name="hostel">
            </div>

            <div class="form-actions" style="display:flex; gap:10px; margin-top:20px;">
                <a href="manage_staff.php" class="btn btn-back">← Back</a>
                <button type="submit" name="submit" class="leave-btn">Add Staff</button>
            </div>

        </form>
    </div>

</div>

</body>
</html>