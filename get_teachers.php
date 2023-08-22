<?php
// Database credentials (Replace with your actual database credentials)
$host = 'sql.freedb.tech';
$database = 'freedb_yashtingre';
$username = 'freedb_yashtingre';
$dbPassword = 'MeEG9Ty6cy$8y4K';
$port = 3306;

// Create a new PDO instance
$pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $dbPassword);

// Fetch all teachers from the database
$stmt = $pdo->prepare("SELECT * FROM teachers");
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return teachers as JSON response
header('Content-Type: application/json');
echo json_encode($teachers);
?>
