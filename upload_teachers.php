<?php
// Database credentials (Replace with your actual database credentials)
$host = 'sql.freedb.tech';
$database = 'freedb_yashtingre';
$username = 'freedb_yashtingre';
$dbPassword = 'MeEG9Ty6cy$8y4K';
$port = 3306;

// Create a new PDO instance
$pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $dbPassword);

// Handle teacher import from CSV
if (isset($_FILES['csvFile'])) {
    $uploadedFile = $_FILES['csvFile'];

    // Check for upload errors
    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        $csvFileName = $uploadedFile['tmp_name'];

        // Import teachers from CSV to the database
        $file = fopen($csvFileName, 'r');
        if ($file) {
            while (($data = fgetcsv($file)) !== false) {
                $teacherName = $data[0];
                $username = $data[1];
                $password = password_hash($data[2], PASSWORD_DEFAULT); // Hash the password before storing

                // Prepare the insert statement
                $stmt = $pdo->prepare("INSERT INTO teachers (teacherName, username, password) VALUES (?, ?, ?)");
                $stmt->execute([$teacherName, $username, $password]);
            }
            fclose($file);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
