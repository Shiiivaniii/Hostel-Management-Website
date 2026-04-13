<?php
session_start();

// Only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_SESSION['hostel'])) {
    header("Location: ../signin.php");
    exit();
}

// MySQL credentials
$host = "localhost";
$user = "root";
$password = "";
$database = $_SESSION['hostel']; // admin's hostel DB

// Create DB connection
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ===== UPDATE STATUS IF BUTTON CLICKED ===== */
if (isset($_GET['resolve_id'])) {
    $id = $_GET['resolve_id'];

    $stmt = $conn->prepare("UPDATE complaints SET status='Resolved' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Refresh page after update
    header("Location: admin_dashboard.php");
    exit();
}

/* ===== FETCH ALL COMPLAINTS ===== */
$result = $conn->query("SELECT * FROM complaints ORDER BY id DESC");
if(!$result){
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
  
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-dashboard">

<main>

  <div class="layout-grid">
    <div class="card">
      <div class="card-inner">
      <!-- Back Button -->
      <a href="../hostel.php" class="admin-back-btn">← Back</a>
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">   
        <div>
                <h2 class="card-title">Admin Complaint Dashboard</h2>
                <p class="card-subtitle">
                View all registered complaints
                </p>
            </div>

            <div>
                <span style="margin-right:10px;">
           <?php echo $_SESSION['name'] ?? "Admin"; ?>
                </span>
                <a href="../logout.php" class="logout-btn">Logout</a>
        </div>

        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Room</th>
                <th>Block</th>
                <th>Category</th>
                <th>Priority</th>
                <th>View</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {

                    $statusClass = ($row['status'] == 'Pending')
                        ? "status-pending"
                        : "status-resolved";

                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['student_name']."</td>";
                    echo "<td>".$row['room_number']."</td>";
                    echo "<td>".$row['hostel_block']."</td>";
                    echo "<td>".$row['category']."</td>";
                    echo "<td>".$row['priority']."</td>";
                    echo "<td>
        <button class='view-btn'
            data-name='".$row['student_name']."'
            data-room='".$row['room_number']."'
            data-block='".$row['hostel_block']."'
            data-category='".$row['category']."'
            data-priority='".$row['priority']."'
            data-status='".$row['status']."'
            data-complaint=\"".$row['details']."\">
            View
        </button>
      </td>";
                
echo "<td><span class='$statusClass'>".$row['status']."</span></td>";

echo "<td>";

if ($row['status'] == 'Pending') {
    echo "<a href='?resolve_id=".$row['id']."' 
            onclick=\"return confirm('Mark this complaint as resolved?')\">
            <button class='resolve-btn'>Mark as Resolved</button>
          </a>";
} else {
    echo "-";
}

echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No complaints found</td></tr>";
            }
            ?>

            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</main>
<!-- Complaint View Modal -->
<div id="complaintModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h3>Complaint Details</h3>
    <p id="modalContent"></p>
  </div>
</div>
<script>
const modal = document.getElementById("complaintModal");
const modalContent = document.getElementById("modalContent");
const closeBtn = document.querySelector(".close-btn");

document.querySelectorAll(".view-btn").forEach(button => {
    button.addEventListener("click", function() {
        const complaint = this.getAttribute("data-complaint");

        modalContent.innerHTML = "<strong>Complaint:</strong><br><br>" + complaint;
        modal.style.display = "flex";
    });
});

closeBtn.onclick = function() {
    modal.style.display = "none";
};

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
</script>
</body>
</html>
