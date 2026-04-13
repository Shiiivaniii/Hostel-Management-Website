<?php
session_start();
include "db.php";

$result = $conn->query("SELECT * FROM staff ORDER BY staff_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Staff</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="style1.css" />
</head>

<body class="manage-staff">

<div class="page-wrapper">

    <header>
       <pre>      <a href="add_staff.php" class="btn btn-add">+ Add Staff</a></pre>
        <a href="hostel.php" class="btn btn-back">← Back</a>
    </header>

    <div class="card-container">

        <?php while($row = $result->fetch_assoc()) { ?>

        <div class="staff-card">

            <div class="staff-title">
                <?php echo $row['position']; ?>
            </div>

            <div class="staff-info">
                <strong>Name:</strong> <?php echo $row['name']; ?>
            </div>

            <div class="staff-info">
                <strong>Email:</strong> <?php echo $row['email']; ?>
            </div>

            <?php if(!empty($row['contact'])) { ?>
            <div class="staff-info">
                <strong>Phone:</strong> <?php echo $row['contact']; ?>
            </div>
            <?php } ?>

            <?php if(!empty($row['hostel_assigned'])) { ?>
            <div class="staff-info">
                <strong>Hostel:</strong> <?php echo $row['hostel_assigned']; ?>
            </div>
            <?php } ?>

            <div class="actions">
                <a href="edit_staff.php?id=<?php echo $row['staff_id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_staff.php?id=<?php echo $row['staff_id']; ?>"
                   class="btn btn-delete"
                   onclick="return confirm('Are you sure you want to delete this staff?');">
                   Delete
                </a>
            </div>

        </div>

        <?php } ?>

    </div>

</div>

</body>
</html>