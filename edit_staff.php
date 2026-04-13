<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include 'db.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM staff WHERE staff_id=$id");
$staff = $result->fetch_assoc();

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $position = $_POST['position'];
    $hostel = $_POST['hostel'];

    $conn->query("UPDATE staff SET name='$name', email='$email', contact='$contact', position='$position', hostel_assigned='$hostel' WHERE staff_id=$id");
    header("Location: manage_staff.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Staff</title>
    <link rel="stylesheet" href="style1.css" />
</head>
<body class="edit-staff">

 <div class="page-wrapper edit-staff">

    <div class="edit-card">
        <h1>Edit Staff Member</h1>

      <form method="POST" class="edit-form">
    <input type="text" name="name" value="<?= $staff['name'] ?>" placeholder="Name" required>
    <input type="email" name="email" value="<?= $staff['email'] ?>" placeholder="Email" required>
    <input type="text" name="contact" value="<?= $staff['contact'] ?>" placeholder="Contact Number">
    <input type="text" name="position" value="<?= $staff['position'] ?>" placeholder="Position">
    <input type="text" name="hostel" value="<?= $staff['hostel_assigned'] ?>" placeholder="Hostel Assigned">

    <div class="form-actions">
        <a href="manage_staff.php" class="back-link">← Back</a>
        <button type="submit" name="submit">Update Staff</button>
    </div>
</form>

    </div>

</div>
</body>
</html>