<?php
require_once '../dbconfig.php';

echo "<h2>Database Update Script</h2>";

// Add status column
$checkStatus = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'status'");
if (mysqli_num_rows($checkStatus) == 0) {
    $result = mysqli_query($conn, "ALTER TABLE complain ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
    if ($result) {
        echo "<p>✓ Added 'status' column to complain table</p>";
    } else {
        echo "<p>✗ Error adding 'status' column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>✓ 'status' column already exists</p>";
}

// Add priority column
$checkPriority = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'priority'");
if (mysqli_num_rows($checkPriority) == 0) {
    $result = mysqli_query($conn, "ALTER TABLE complain ADD COLUMN priority VARCHAR(10) DEFAULT 'medium'");
    if ($result) {
        echo "<p>✓ Added 'priority' column to complain table</p>";
    } else {
        echo "<p>✗ Error adding 'priority' column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>✓ 'priority' column already exists</p>";
}

// Add assigned_to column
$checkAssigned = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'assigned_to'");
if (mysqli_num_rows($checkAssigned) == 0) {
    $result = mysqli_query($conn, "ALTER TABLE complain ADD COLUMN assigned_to VARCHAR(50) DEFAULT NULL");
    if ($result) {
        echo "<p>✓ Added 'assigned_to' column to complain table</p>";
    } else {
        echo "<p>✗ Error adding 'assigned_to' column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>✓ 'assigned_to' column already exists</p>";
}

// Add created_at column
$checkCreated = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'created_at'");
if (mysqli_num_rows($checkCreated) == 0) {
    $result = mysqli_query($conn, "ALTER TABLE complain ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    if ($result) {
        echo "<p>✓ Added 'created_at' column to complain table</p>";
    } else {
        echo "<p>✗ Error adding 'created_at' column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>✓ 'created_at' column already exists</p>";
}

// Add updated_at column
$checkUpdated = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'updated_at'");
if (mysqli_num_rows($checkUpdated) == 0) {
    $result = mysqli_query($conn, "ALTER TABLE complain ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    if ($result) {
        echo "<p>✓ Added 'updated_at' column to complain table</p>";
    } else {
        echo "<p>✗ Error adding 'updated_at' column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>✓ 'updated_at' column already exists</p>";
}

// Fix email column size (it's currently only 3 characters)
$result = mysqli_query($conn, "ALTER TABLE complain MODIFY COLUMN email VARCHAR(100) NOT NULL");
if ($result) {
    echo "<p>✓ Fixed 'email' column size to 100 characters</p>";
} else {
    echo "<p>✗ Error fixing 'email' column: " . mysqli_error($conn) . "</p>";
}

// Update existing records with default status
$result = mysqli_query($conn, "UPDATE complain SET status = 'pending' WHERE status IS NULL OR status = ''");
if ($result) {
    echo "<p>✓ Updated existing records with default status</p>";
} else {
    echo "<p>✗ Error updating existing records: " . mysqli_error($conn) . "</p>";
}

echo "<h3>Database Update Complete!</h3>";
echo "<p><a href='index.php'>Go to Admin Panel</a></p>";
?>
