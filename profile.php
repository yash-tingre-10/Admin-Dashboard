<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database credentials
$host = 'sql.freedb.tech';
$database = 'freedb_yashtingre';
$username = 'freedb_yashtingre';
$dbPassword = 'MeEG9Ty6cy$8y4K';
$port = 3306;

// Create a new PDO instance
$pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $dbPassword);

// Retrieve user data from the database
$stmt = $pdo->prepare("SELECT firstName, lastName, email, gender, profilePhoto, phoneNumber, address FROM users WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Store user data in session
$_SESSION['firstName'] = $userData['firstName'];
$_SESSION['lastName'] = $userData['lastName'];
$_SESSION['email'] = $userData['email'];
$_SESSION['gender'] = $userData['gender'];
$_SESSION['profilePhoto'] = $userData['profilePhoto'];
$_SESSION['phoneNumber'] = $userData['phoneNumber'];
$_SESSION['address'] = $userData['address'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <link rel="stylesheet" href="profileStyle.css">
</head>
<body>

  <!-- navbar start -->

  <nav class="navbar">
        <ul class="navbar-list">
            <li><a href="profile.php">Profile</a></li>
            <li><a href="index.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
  
  <!-- navbar end -->
  
    <div class="container">
        <div class="card">
            <h2 class="welcome-msg">Welcome, <?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName']; ?></h2>
            <div class="profile-info">
                <p class="info">First Name: <?php echo $_SESSION['firstName']; ?></p>
                <p class="info">Last Name: <?php echo $_SESSION['lastName']; ?></p>
                <p class="info">Email: <?php echo $_SESSION['email']; ?></p>
                <p class="info">Gender: <?php echo $_SESSION['gender']; ?></p>
                <p class="info">Phone Number: <?php echo $_SESSION['phoneNumber']; ?></p>
                <p class="info">Address: <?php echo $_SESSION['address']; ?></p>
                <div class="profile-photo">
                    <?php if (!empty($_SESSION['profilePhoto'])): ?>
                        <img src="<?php echo $_SESSION['profilePhoto']; ?>" alt="Profile Photo">
                    <?php else: ?>
                        <p>No profile photo available</p>
                    <?php endif; ?>
                </div>
            </div>

            <form method="POST" action="logout.php">
                <button class="logout-button" type="submit">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
