<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) header("Location: signin.html");

// Configuration
$floors = [0 => "Ground Floor", 1 => "First Floor", 2 => "Second Floor", 3 => "Third Floor"];
$blocks = ['A','B','C'];
$rooms_per_block = 26;
$block_offsets = ['A'=>0, 'B'=>26, 'C'=>52];

// Fetch all rooms properly
$result = $conn->query("SELECT rooms.*, users.name AS student_name
                        FROM rooms
                        LEFT JOIN users ON rooms.student_id = users.id");

$rooms_data = [];

while($row = $result->fetch_assoc()){
    $rooms_data[$row['floor']][$row['block']][$row['room_number']] = $row;
}

// Correct taken count (ONLY if student_id NOT NULL)
$taken_count = 0;
foreach($rooms_data as $floor){
    foreach($floor as $block){
        foreach($block as $room){
            if (!empty($room['student_id'])) {
                $taken_count++;
            }
        }
    }
}

$total_rooms = count($floors) * count($blocks) * $rooms_per_block;
$vacant_count = $total_rooms - $taken_count;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hostel Rooms</title>
    <link rel="stylesheet" href="room.css">
</head>
<body>
<div class="container">
    <header class="staf-header">
    <a href="hostel.php" class="back-btn">← Back</a>
    </header>
    <h2>Hostel Rooms</h2>

    <div class="counts">
        <span>Taken Rooms: <?= $taken_count ?></span> |
        <span>Vacant Rooms: <?= $vacant_count ?></span>
    </div>

    <button id="viewMapBtn">View Room Map</button>

    <!-- Filter -->
    <div class="filter" style="margin:10px 0;">
        <label>
            Block
            <select id="filterBlock">
                <option value="">All</option>
                <?php foreach($blocks as $b) echo "<option value='$b'>$b</option>"; ?>
            </select>
        </label>
        <label>
            Floor
            <select id="filterFloor">
                <option value="">All</option>
                <?php foreach($floors as $num=>$name) echo "<option value='$num'>$name</option>"; ?>
            </select>
        </label>
        <button class="btn-filter" onclick="applyFilter()">Filter</button>
    </div>

    <!-- Table of all rooms -->
    <table id="roomsTable" border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Room No</th>
                <th>Block</th>
                <th>Floor</th>
                <th>Student ID</th>
                <th>Student Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Generate full table from all rooms
            foreach($floors as $floor_num=>$floor_name){
                foreach($blocks as $block){
                    $base = ($floor_num+1)*100;
                    $offset = $block_offsets[$block];
                    for($i=0;$i<$rooms_per_block;$i++){
                        $room_number = $base + $offset + $i;
                       $student = $rooms_data[$floor_num][$block][$room_number] ?? null;
                        echo "<tr>";
                        echo "<td>$room_number</td>";
                        echo "<td>$block</td>";
                        echo "<td>$floor_num</td>";
                       echo "<td>".($student['student_id'] ?? '-')."</td>";
echo "<td>".($student['student_name'] ?? '-')."</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Room Map -->
<div class="modal" id="roomMapModal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>

        <div class="counts">
            <span>Taken Rooms: <?= $taken_count ?></span> |
            <span>Vacant Rooms: <?= $vacant_count ?></span>
        </div>

        <?php
        foreach($floors as $floor_num=>$floor_name):
        ?>
            <div class="floor">
                <h3><?= $floor_name ?></h3>
                <?php foreach($blocks as $block): ?>
                    <div class="block-title">Block <?= $block ?></div>
                    <div class="rooms-grid">
                        <?php
                        $base = ($floor_num+1)*100;
                        $offset = $block_offsets[$block];
                        for($i=0;$i<$rooms_per_block;$i++):
                            $room_number = $base + $offset + $i;
                          $student = $rooms_data[$floor_num][$block][$room_number] ?? null;
$isTaken = !empty($student['student_id']);

$class = $isTaken ? 'taken' : 'vacant';
$label = $isTaken ? 'Taken' : 'Vacant';
                        ?>
                       <div class="room-box <?= $class ?>"
     data-room="<?= $room_number ?>"
     data-block="<?= $block ?>"
     data-floor="<?= $floor_num ?>"
     data-student="<?= $student['student_id'] ?? '' ?>">
    <?= $room_number ?><br><?= $label ?>
</div>
                        <?php endfor; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="tooltip-custom" id="tooltip"></div>
    
</div>
<!-- Assign / Vacate Modal -->
<div class="modal" id="actionModal" style="display:none;">
  <div class="modal-content">
    <span class="modal-close" id="closeAction">&times;</span>
    <h3 id="actionTitle"></h3>

    <!-- Assign Form -->
    <form id="assignForm" style="display:none;">
      <input type="hidden" name="room_number" id="roomNumber">
      <input type="hidden" name="block" id="roomBlock">
      <input type="hidden" name="floor" id="roomFloor">

      <label>Student ID</label>
      <input type="number" name="student_id" required style="width:100%;padding:8px;margin:10px 0;">

      <button type="submit" class="btn-filter">Assign</button>
    </form>

    <!-- Vacate Section -->
    <div id="vacateSection" style="display:none;">
      <p>Are you sure you want to vacate this room?</p>
      <button id="vacateBtn" class="btn-filter">Yes, Vacate</button>
    </div>

  </div>
</div>
<script src="rooms.js"></script>
</body>
</html>