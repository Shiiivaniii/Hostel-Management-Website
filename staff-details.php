<?php
session_start();
include "db.php"; // make sure this connects to hostel_db

$result = $conn->query("SELECT * FROM staff");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Hostel 1 – Staff Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style1.css" />
</head>
<body class="staff-details">
  <div class="page-wrapper">
    <header class="staf-header">
    <a href="hostel.php" class="back-btn">← Back</a>
    </header>

    <section class="card staff-list-card">
      <h2>Key Staff Members</h2>

      <div  class="staff-list">

        <?php while($row = $result->fetch_assoc()) { ?>
        
        <div class="staff-card">
          
          <div  class="staff-position">
            <?php echo $row['position']; ?>
          </div>

          <div class="staff-name">
            <?php echo $row['name']; ?>
          </div>

          <div  class="staff-contact">
            Phone: <?php echo $row['contact']; ?> 
            <?php if(!empty($row['email'])) { ?>
              • Email: <?php echo $row['email']; ?>
            <?php } ?>
          </div>

          <?php if(!empty($row['office_hours'])) { ?>
          <div  class="staff-hours">
            Office Hours: <?php echo $row['office_hours']; ?>
          </div>
          <?php } ?>

        </div>
        <?php } ?>

      </div>
    </section>
  </div>
</body>
</html>