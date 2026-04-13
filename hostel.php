<?php
session_start();
include 'db.php'; // connect to selected hostel DB

// Optional: Make sure only logged-in users can access
if(!isset($_SESSION['role'])){
    header("Location: signin.php");
    exit();
}
?>
<?php
// Start session only once
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

$announcement_result = $conn->query("SELECT * FROM announcements ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Hostel 1 – Announcements</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style1.css">
  <script defer src="script.js"></script>
  <style>
    * { text-decoration: none; }
    a { color: white; margin-left: 20px; }
  </style>
</head>
<body>
<div class="top-nav">
  <div class="brand">
      <img src="OIP.webp" alt="College Logo" class="navbar__logo-img">
      <div class="brand-text">NIT KKR</div>
  </div>
  <div class="top-actions">
      <span style="margin-right:10px;">
        <?php
        // Display login/logout dynamically
        if (isset($_SESSION['user_name'])) {
            echo "Welcome, " . htmlspecialchars($_SESSION['user_name']);
            echo ' | <a href="logout.php">Logout</a>';
        } else {
            echo '<a href="signin.php">Login</a>';
        }
        ?>
      </span>
  </div>
</div>

<div class="page-wrapper">
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Somewhere in hostel.php, e.g., at the top of the page -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div style="margin: 10px 0;">
        <a href="index.php" class="btn btn-primary">← Back</a>
    </div>
<?php endif; ?>
  <section class="card">
    <h2>Announcements</h2>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
      <div class="admin-tools-inline">
          <button type="button" id="open-announcement-modal" class="btn-staff admin-add-trigger">
              Add Announcement
          </button>
      </div>
    <?php endif; ?>

    <ul id="announcements-list" class="announcements-list">
      <?php if ($announcement_result->num_rows > 0): ?>
          <?php while($row = $announcement_result->fetch_assoc()): ?>
          <li>
              <div class="announcement-title">
                  <?= htmlspecialchars($row['title']) ?>
              </div>
              <div class="announcement-meta">
                  <?= htmlspecialchars($row['subtitle']) ?> • Created: <?= date("d M Y • h:i A", strtotime($row['created_at'])) ?>
              </div>
              <div class="announcement-body">
                  <?= nl2br(htmlspecialchars($row['body'])) ?>
              </div>
              <?php if (!empty($row['attachment'])): ?>
                  <div class="announcement-attachment">
                      <a href="<?= $row['attachment'] ?>" target="_blank">📎 View Attachment</a>
                  </div>
              <?php endif; ?>
          </li>
          <?php endwhile; ?>
      <?php else: ?>
          <li>No announcements available.</li>
      <?php endif; ?>
    </ul>
  </section>
</div>

<!-- Admin announcement modal -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
<div id="announcement-modal" class="admin-only admin-modal-overlay" hidden>
  <div class="admin-modal">
    <div class="admin-modal-header">
      <h3>Add Announcement</h3>
      <button type="button" id="close-announcement-modal" class="admin-modal-close">&times;</button>
    </div>
    <form id="add-announcement-form" action="add_announcement.php" method="POST" enctype="multipart/form-data">
      <div class="admin-form-grid">
        <div>
          <label for="new-title">Title</label>
          <input id="new-title" name="title" type="text" required />
        </div>
        <div>
          <label for="new-Subtitle">Subtitle</label>
          <input id="new-Subtitle" name="subtitle" type="text" required />
        </div>
        <div>
          <label for="new-body">Details</label>
          <textarea id="new-body" name="body" rows="2" required></textarea>
        </div>
        <div>
          <label>Upload Attachment (optional)</label>
          <input type="file" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        </div>
      </div>
      <div class="admin-tools-actions">
        <button type="submit" class="btn-staff admin-add-btn">Save Announcement</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Bottom buttons -->
<div class="bottom-bar">
    <a href="rooms.php" class="btn-staff">Room Allocation</a>
    <?php if (isset($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] == 'student'): ?>
            <a href="complaint/index.php">Register Complaint</a>
            <a href="staff-details.php" class="btn-staff">Staff Details</a>
            <a href="leave.php" class="btn-staff">Apply for Leave</a>
        <?php elseif ($_SESSION['role'] == 'admin'): ?>
            <a href="complaint/admin_dashboard.php">View Complaints</a>
            <a href="manage_staff.php">Manage Staff</a>
            <a href="manage_leaves.php">View Student Leaves</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>