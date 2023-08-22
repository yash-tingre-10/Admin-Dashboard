<?php
session_start();

if(isset($_POST['login'])){
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database credentials
    $host = 'sql.freedb.tech';
    $database = 'freedb_yashtingre';
    $username = 'freedb_yashtingre';
    $dbPassword = 'MeEG9Ty6cy$8y4K';
    $port = 3306;

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $dbPassword);

    // Prepare the select statement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");

    // Bind parameters
    $stmt->bindParam(1, $email);
    $stmt->bindParam(2, $password);

    // Execute the statement
    $stmt->execute();

    // Check if a matching user is found
    if ($stmt->rowCount() > 0) {
        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Store user data in session
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['gender'] = $user['gender'];

        // Redirect to profile page
        header("Location: profile.php");
        exit();
    } else {
        // Placeholder error message
        $errorMessage = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="loginStyle.css">
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
        <h2 style="text-align: center;">Login Page</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" minlength="8" required>
            </div>
            <div class="form-group">
                <input type="submit" name="login" value="Login">
            </div>
        </form>

        <?php
        if(isset($errorMessage)){
            echo "<p class=\"error-message\">$errorMessage</p>";
        }
        ?>
    </div>
</body>
</html>
