<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || !isset($_SESSION['hostel'])) {
    header("Location: ../signin.php");
    exit();
}

// MySQL credentials
$host = "localhost";
$user = "root";
$password = "";
$database = $_SESSION['hostel']; // use the logged-in user's hostel DB

// Create connection
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = null;

if (isset($_GET['complaint_id'])) {
    $id = $_GET['complaint_id'];

    $stmt = $conn->prepare("SELECT * FROM complaints WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Complaint Status</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="status">
  <div class="layout-grid">
    <div class="card">
      <div class="card-inner">

        <div class="card-header">
          <div>
            <a href="/SL_project/complaint/index.php" class="btn btn-primary">⬅ Back</a>
            
            <h2 class="card-title" style="margin-top: 10px;">Track Complaint</h2>
            <p class="card-subtitle">
              Enter your complaint ID to check current status
            </p>
          </div>
        </div>

        <form method="GET">
          <div class="form-group">
            <label>Complaint ID</label>
            <input type="number" name="complaint_id" required>
          </div>

          <button type="submit" class="btn-primary" >
            Check Status
          </button>
        </form>

        <!-- RESULT SECTION -->
        <?php
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $statusClass = ($row['status'] == 'Pending')
                ? "status-pending"
                : "status-resolved";

            echo "<div style='margin-top:30px;'>";

            echo "<div class='form-group'>";
            echo "<label>Status</label>";
            echo "<div class='$statusClass'>" . $row['status'] . "</div>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<label>Complaint Details</label>";
            echo "<div class='helper-text'>" . $row['details'] . "</div>";
            echo "</div>";

            echo "</div>";

        } elseif (isset($_GET['complaint_id'])) {
            echo "<div class='helper-text' style='color:#f87171; margin-top:20px;'>Complaint not found</div>";
        }
        ?>

      </div>
    </div>
  </div>
</main>

</body>
</html>