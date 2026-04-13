<?php
session_start();

// MySQL credentials (same for all DBs)
$host = "localhost";
$user = "root";
$password = "";

// Hostel DBs
$hostelDBs = ['cauvery', 'bhagirathi', 'alaknanda', 'kalpana'];

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $pass = $_POST['password'];

    $found = false;

    // 1️⃣ First, check if user is an admin in any DB
    foreach ($hostelDBs as $dbName) {
        $conn = new mysqli($host, $user, $password, $dbName);
        if ($conn->connect_error) continue;

        $stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND password=? AND role='admin' LIMIT 1");
        $stmt->bind_param("ss", $id, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['hostel'] = $dbName; // admin's DB

            // Admin goes to index.php (or hostel selection)
            header("Location: index.php");
            exit();
        }
        $conn->close();
    }

    // 2️⃣ If not admin, check the student's assigned hostel DB
    foreach ($hostelDBs as $dbName) {
        $conn = new mysqli($host, $user, $password, $dbName);
        if ($conn->connect_error) continue;

        $stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND password=? AND role!='admin' LIMIT 1");
        $stmt->bind_param("ss", $id, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['hostel'] = $dbName; // the student's hostel DB

            // Redirect to student hostel page
            header("Location: hostel.php");
            exit();
        }
        $conn->close();
    }

    // If no match found
    $error = "Incorrect ID or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Hostel Management – Sign In</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style1.css" />
</head>
<body class="signin-page">
  <div class="page-wrapper">
    <header>
      <h1>Sign In</h1>
      <p>Sign in to continue.</p>
    </header>

    <section class="card">
      <h2>Account</h2>

      <?php if(!empty($error)): ?>
        <div style="color:red; font-weight:bold; margin-bottom:10px;"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST" action="signin.php" id="sign-in-form" style="display:grid; gap:12px; margin-top:10px;">
        <div>
          <label for="signin-id">ID</label>
          <input type="text" name="id" required>
        </div>
        
        <div>
          <label for="signin-password">Password</label>
          <input type="password" name="password" required>
        </div>
        <div>
          <button type="submit" class="signin-submit-btn">Sign In</button>
        </div>
      </form>
    </section>
  </div>
</body>
</html>