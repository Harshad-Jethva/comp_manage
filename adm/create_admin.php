<?php
require_once '../dbconfig.php';

echo "<h2>Create Admin User with Hashed Password</h2>";

// Create admin user with properly hashed password
$username = 'admin';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if admin_users table exists, if not create it
$checkTable = "SHOW TABLES LIKE 'admin_users'";
$result = mysqli_query($conn, $checkTable);

if (mysqli_num_rows($result) == 0) {
    // Create admin_users table
    $createTable = "
    CREATE TABLE `admin_users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `full_name` varchar(100) NOT NULL,
        `email` varchar(100) NOT NULL UNIQUE,
        `role` enum('super_admin','admin','officer') DEFAULT 'officer',
        `status` enum('active','inactive') DEFAULT 'active',
        `last_login` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_username` (`username`),
        UNIQUE KEY `unique_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (mysqli_query($conn, $createTable)) {
        echo "<p>✓ Created admin_users table</p>";
    } else {
        echo "<p>✗ Error creating admin_users table: " . mysqli_error($conn) . "</p>";
    }
}

// Check if admin user already exists
$checkAdmin = "SELECT * FROM admin_users WHERE username = 'admin'";
$result = mysqli_query($conn, $checkAdmin);

if (mysqli_num_rows($result) > 0) {
    // Update existing admin user
    $updateAdmin = "UPDATE admin_users SET password = '$hashed_password' WHERE username = 'admin'";
    if (mysqli_query($conn, $updateAdmin)) {
        echo "<p>✓ Updated admin user password with hash</p>";
    } else {
        echo "<p>✗ Error updating admin user: " . mysqli_error($conn) . "</p>";
    }
} else {
    // Insert new admin user
    $insertAdmin = "INSERT INTO admin_users (username, password, full_name, email, role, status) 
                   VALUES ('admin', '$hashed_password', 'System Administrator', 'admin@fixbot.com', 'super_admin', 'active')";
    if (mysqli_query($conn, $insertAdmin)) {
        echo "<p>✓ Created admin user with hashed password</p>";
    } else {
        echo "<p>✗ Error creating admin user: " . mysqli_error($conn) . "</p>";
    }
}

echo "<h3>Admin Login Details:</h3>";
echo "<p><strong>Username:</strong> admin</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p><strong>Hashed Password:</strong> " . $hashed_password . "</p>";

echo "<p><a href='login.php'>Go to Login Page</a></p>";
?>
