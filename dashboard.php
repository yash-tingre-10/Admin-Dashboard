<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: adminLogin.php");
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

// Add User
if (isset($_POST['add-user'])) {
    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];

    // Prepare the insert statement
    $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password, gender, address, phoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $password, $gender, $address, $phoneNumber]);

    header("Location: dashboard.php");
    exit();
}

// Update User
if (isset($_POST['update-user'])) {
    // Retrieve form data
    $userId = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];

    // Update user data in the database
    $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ?, password = ?, gender = ?, address = ?, phoneNumber = ? WHERE id = ?");
    $stmt->execute([$firstName, $lastName, $email, $password, $gender, $address, $phoneNumber, $userId]);

    header("Location: dashboard.php");
    exit();
}

// Delete User
if (isset($_POST['delete-user'])) {
    $userId = $_POST['userId'];

    // Delete user from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    header("Location: dashboard.php");
    exit();
}

// Retrieve all users from the database
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Add your CSS code for the dashboard here */
        /* Styling for the user table */
.user-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.user-table th, .user-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

.user-table th {
    background-color: #f2f2f2;
}

.user-table img {
    max-width: 100px;
    max-height: 100px;
}

.user-table .editable {
    cursor: pointer;
}

.user-table .editing {
    border: 1px solid #aaa;
    background-color: #f9f9f9;
}

.user-table .edit-btn, .user-table .save-btn, .user-table .delete-btn {
    padding: 5px 10px;
    cursor: pointer;
    margin-right: 5px;
}

    </style>
</head>
<body>
  
    <div class="container">
        <h3>Add User</h3>
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

            <input type="submit" name="add-user" value="Add User">
        </form>

  <h2><a href="import.php"> Add Teacher </a> </h2>
      
        <h3>User List</h3>
        <table class="user-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Profile Photo</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr data-id="<?php echo $user['id']; ?>">
                <td><?php echo $user['id']; ?></td>
                <td class="editable" data-field="firstName"><?php echo $user['firstName']; ?></td>
                <td class="editable" data-field="lastName"><?php echo $user['lastName']; ?></td>
                <td class="editable" data-field="email"><?php echo $user['email']; ?></td>
                <td class="editable" data-field="password"><?php echo $user['password']; ?></td>
                <td class="editable" data-field="gender"><?php echo $user['gender']; ?></td>
                <td class="editable" data-field="address"><?php echo $user['address']; ?></td>
                <td class="editable" data-field="phoneNumber"><?php echo $user['phoneNumber']; ?></td>
                <td>
                    <?php if (!empty($user['profilePhoto'])): ?>
                        <img src="<?php echo $user['profilePhoto']; ?>" alt="Profile Photo" width="100">
                    <?php else: ?>
                        <p>No profile photo available</p>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Event handler for edit buttons
            $(document).on('click', '.edit-btn', function() {
                var $row = $(this).closest('tr');
                var userId = $row.data('id');

                // Enable editing
                $row.find('.editable').prop('contenteditable', true).addClass('editing');

                // Change button text
                $(this).text('Save').removeClass('edit-btn').addClass('save-btn');
            });

            // Event handler for save buttons
            $(document).on('click', '.save-btn', function() {
                var $row = $(this).closest('tr');
                var userId = $row.data('id');
                var updateData = {};

                // Collect updated data
                $row.find('.editable.editing').each(function() {
                    var field = $(this).data('field');
                    var value = $(this).text();
                    updateData[field] = value;
                });

                // Add userId to updateData
                updateData['userId'] = userId;

                // Send update request
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: { ...updateData, 'update-user': true },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Disable editing
                            $row.find('.editable').prop('contenteditable', false).removeClass('editing');

                            // Change button text
                            $row.find('.save-btn').text('Edit').removeClass('save-btn').addClass('edit-btn');

                            // Update the displayed data
                            $.each(updateData, function(field, value) {
                                $row.find(`.editable[data-field="${field}"]`).text(value);
                            });

                          // Reaload Page after update updateData
                          location.reload();
                          
                        }
                      
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Event handler for delete buttons
            $(document).on('click', '.delete-btn', function() {
                var $row = $(this).closest('tr');
                var userId = $row.data('id');

                // Send delete request
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: { userId: userId, 'delete-user': true },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Remove the deleted row
                            $row.remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
