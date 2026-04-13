<?php
// db.php
// Make sure session_start() is already called in the page that includes this

$host = "localhost";
$user = "root";
$password = "";

// Map admin hostel selection keys to actual database names
$hostel_db_map = [
    'cauvery'    => 'cauvery',
    'bhagirathi' => 'bhagirathi',
    'kalpana'    => 'kalpana',
    'alaknanda'  => 'alaknanda'
];

// Decide which database to connect to
if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] === 'admin') {
        if (isset($_SESSION['hostel'])) {
            $database = $hostel_db_map[$_SESSION['hostel']] ?? die("Invalid hostel selected.");
        } else {
            // Redirect admin to choose hostel page
            header("Location: choose_hostel.php");
            exit();
        }

    } elseif ($_SESSION['role'] === 'student') {
        if (isset($_SESSION['hostel'])) {
            $database = $_SESSION['hostel']; // student's hostel stored in users table at login
        } else {
            die("Your hostel is not set. Contact admin.");
        }

    } else {
        header("Location: signin.php");
        exit();
    }

} else {
    // Not logged in → redirect to login page
    header("Location: signin.php");
    exit();
}

// Create MySQL connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>