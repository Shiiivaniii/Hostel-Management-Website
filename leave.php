<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Allow ONLY students */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Leave Application</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="style1.css" />

</head>

<body class="leave-page">



  <div class="leave-card">
       <a href="hostel.php" class="back-btn">← Back</a>
      <h2>Leave Application</h2>
      <div class="leave-subtitle">
          Fill in the details to submit your leave request.
      </div>

      <form action="add_leave.php" method="POST" class="leave-form">

        <div class="form-row">
            <div class="form-group">
              <label>Full Name</label>
              <input name="student_name" type="text" required>
            </div>

            <div class="form-group">
              <label>Room Number</label>
              <input name="room_number" type="text" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
              <label>From Date</label>
              <input name="from_date" type="date" required>
            </div>

            <div class="form-group">
              <label>To Date</label>
              <input name="to_date" type="date" required>
            </div>
        </div>

        <div class="form-group">
          <label>Reason</label>
          <textarea name="reason" rows="4" required></textarea>
        </div>

        <div class="form-group">
          <label>Contact Number</label>
          <input name="contact_number" type="tel" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="leave-btn">
                Submit Leave Request
            </button>
        </div>

      </form>

  </div>



</body>
</html>