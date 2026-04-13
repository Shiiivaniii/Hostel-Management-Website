<?php
session_start();
include 'db.php'; // DB connection

$error = "";

/* ================= LOGIN LOGIC ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $conn->real_escape_string($_POST['id']);
    $password = $conn->real_escape_string($_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE id='$id' AND password='$password' LIMIT 1");

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'student') {
            $_SESSION['hostel'] = $user['hostel'];
            header("Location: hostel.php");
            exit();
        }
    } else {
        $error = "Incorrect ID or password";
    }
}

/* If already logged in student → redirect */
if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    header("Location: hostel.php");
    exit();
}
?>
<head>
    <link rel="stylesheet" href="home.css">
</head>
<body>

<header class="navbar">
  <div class="navbar__brand">
    <img src="OIP.webp" alt="NIT Kurukshetra Logo" class="navbar__logo-img">
    <span class="navbar__brand-text">NIT KKR</span>
  </div>

  <div class="navbar__links">
    <a href="index.php" class="nav-link">Home</a>

    <?php if(isset($_SESSION['role'])): ?>
        <span style="margin-left:20px;">
            Welcome, <?= $_SESSION['user_name']; ?>
        </span>
        <a href="logout.php" class="nav-link">Logout</a>
    <?php endif; ?>
  </div>
</header>


<main>

<?php if(!isset($_SESSION['role'])): ?>

<!-- ================= LOGIN FORM ================= -->
<section style="max-width:400px; margin:40px auto;">
    <?php if(!empty($error)): ?>
        <div style="color:red; font-weight:bold; margin-bottom:10px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="display:grid; gap:12px;">
        <label>ID
            <input type="text" name="id" required>
        </label>

        <label>Password
            <input type="password" name="password" required>
        </label>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</section>

<?php endif; ?>


<!-- ================= HOSTEL SECTION ================= -->
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

<section id="hostels" class="hostels-section">
  <div class="hostel-grid">

    <article class="hostel-card">
      <h3>Cauvery Bhawan</h3>
      <a href="choose_hostel.php?hostel=cauvery" class="btn btn-primary">
        View Details
      </a>
    </article>

    <article class="hostel-card">
      <h3>Bhagirathi Bhawan</h3>
      <a href="choose_hostel.php?hostel=bhagirathi" class="btn btn-primary">
        View Details
      </a>
    </article>

    <article class="hostel-card">
      <h3>Kalpana Chawla Bhawan</h3>
      <a href="choose_hostel.php?hostel=kalpana" class="btn btn-primary">
        View Details
      </a>
    </article>

    <article class="hostel-card">
      <h3>Alaknanda Bhawan</h3>
      <a href="choose_hostel.php?hostel=alaknanda" class="btn btn-primary">
        View Details
      </a>
    </article>

  </div>
</section>

<?php endif; ?>

</main>