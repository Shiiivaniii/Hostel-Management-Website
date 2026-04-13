<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}

include 'db.php';

// Show only active leaves
$today = date('Y-m-d');
$query = "SELECT * FROM leaves WHERE to_date >= '$today' ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin – Leave Requests</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style1.css">
</head>
<body class="manage-leaves">

<div class="leave-page-wrapper">
    <a href="hostel.php" class="back-btn">← Back</a>
   

    <table class="leave-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Room</th>
                <th>From</th>
                <th>To</th>
                <th>Reason</th>
                <th>Contact</th>
                <th>Submitted On</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['room_number']) ?></td>
                <td><?= $row['from_date'] ?></td>
                <td><?= $row['to_date'] ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php $conn->close(); ?>
</body>
</html>