<?php
session_start();

if (isset($_POST['register'])) {
    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];

    // Database credentials
    $host = 'sql.freedb.tech';
    $database = 'freedb_yashtingre';
    $username = 'freedb_yashtingre';
    $dbPassword = 'MeEG9Ty6cy$8y4K';
    $port = 3306;

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $dbPassword);

    // Image upload handling
    if (isset($_FILES['profilePhoto'])) {
        $profilePhoto = $_FILES['profilePhoto'];

        // Imgur API credentials
        $imgurClientId = 'edfc8db79ef4069';

        // Check for upload errors
        if ($profilePhoto['error'] === UPLOAD_ERR_OK) {
            // Create a new cURL resource
            $curl = curl_init();

            // Set cURL options
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.imgur.com/3/image',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'image' => base64_encode(file_get_contents($profilePhoto['tmp_name'])),
                ],
                CURLOPT_HTTPHEADER => [
                    "Authorization: Client-ID $imgurClientId",
                ],
            ]);

            // Send the cURL request
            $response = curl_exec($curl);

            // Close cURL resource
            curl_close($curl);

            // Decode the JSON response
            $responseData = json_decode($response, true);

            // Check if the upload was successful
            if (isset($responseData['data']['link'])) {
                $imageLink = $responseData['data']['link'];

                // Prepare the insert statement with image URL
                $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password, gender, address, phoneNumber, profilePhoto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                // Bind parameters
                $stmt->bindParam(1, $firstName);
                $stmt->bindParam(2, $lastName);
                $stmt->bindParam(3, $email);
                $stmt->bindParam(4, $password);
                $stmt->bindParam(5, $gender);
                $stmt->bindParam(6, $address);
                $stmt->bindParam(7, $phoneNumber);
                $stmt->bindParam(8, $imageLink);

                // Execute the statement
                $stmt->execute();

                // Store user data in session
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;
                $_SESSION['email'] = $email;
                $_SESSION['gender'] = $gender;

                // Redirect to profile page
                header("Location: profile.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="regStyle.css">
    <style>
        /* Add your CSS code for the registration page here */
        /* ... */
    </style>
</head>
<body>
    <!-- navbar start -->
    <nav class="navbar">
        <ul class="navbar-list">
            <li><a href="profile.php">Profile</a></li>
            <li><a href="index.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="adminLogin.php">Admin</a></li>
        </ul>
    </nav>
    <!-- navbar end -->

    <div class="container">
        <h2>Registration Page</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" required>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" minlength="8" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" required>
            </div>

            <div class="form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" name="phoneNumber" required>
            </div>

            <div class="form-group">
                <label for="profilePhoto">Profile Photo:</label>
                <input type="file" name="profilePhoto" accept="image/*" required>
            </div>

            <input type="submit" name="register" value="Register">
        </form>
    </div>
</body>
</html>
