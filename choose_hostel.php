<?php
session_start();

// Only allow admins
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: signin.php");
    exit();
}

// Map selection keys to actual database names
$hostel_db_map = [
    'cauvery'    => 'cauvery',
    'alaknanda'  => 'alaknanda',
    'bhagirathi' => 'bhagirathi',
    'kalpana'    => 'kalpana'
];

// If hostel is selected via GET
if(isset($_GET['hostel'])){
    $hostel = $_GET['hostel'];

    if(!isset($hostel_db_map[$hostel])){
        die("Invalid hostel selection.");
    }

    // Save selected hostel database name in session
    $_SESSION['hostel'] = $hostel_db_map[$hostel];

    // Redirect to admin dashboard
    header("Location: hostel.php");
    exit();
} else {
    die("No hostel selected.");
}