<?php
session_start();
include "db.php";

$floors = [0,1,2,3];
$blocks = ['A','B','C'];
$rooms_per_block = 26;
$block_offsets = ['A'=>0, 'B'=>26, 'C'=>52];

foreach($floors as $floor){
    foreach($blocks as $block){
        $base = ($floor+1)*100;
        $offset = $block_offsets[$block];
        for($i=0;$i<$rooms_per_block;$i++){
            $room_number = $base + $offset + $i;

            $stmt = $conn->prepare("INSERT INTO rooms (room_number, block, floor, student_id) VALUES (?, ?, ?, NULL)");
            $stmt->bind_param("isi", $room_number, $block, $floor);
            $stmt->execute();
        }
    }
}

echo "Rooms inserted successfully!";
?>