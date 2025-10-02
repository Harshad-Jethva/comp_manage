<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../dbconfig.php';

// Function to check admin login
function checkAdminLogin() {
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
        header('Location: login.php');
        exit();
    }
}

// Update getAdminUsers function to fetch from the admin table
function getAdminUsers($conn) {
    $query = "SELECT id, email, role FROM admin WHERE status = '1' ORDER BY email";
    $result = mysqli_query($conn, $query);

    $admins = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $admins[] = $row;
    }

    return $admins;
}

// Add a function to fetch distinct sectors
function getDistinctSectors($conn) {
    $query = "SELECT DISTINCT complaint_sector FROM complain";
    $result = mysqli_query($conn, $query);

    $sectors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sectors[] = $row['complaint_sector'];
    }

    return $sectors;
}

// Function to get all sectors
function getAllSectors($conn) {
    $query = "SELECT * FROM sectors WHERE status = 'active' ORDER BY name";
    $result = mysqli_query($conn, $query);
    
    $sectors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sectors[] = $row;
    }
    
    return $sectors;
}

// Update createComplaint function to match the complain table structure
function createComplaint($conn, $data) {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $location = mysqli_real_escape_string($conn, $data['location']);
    $title = mysqli_real_escape_string($conn, $data['title']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $sector = mysqli_real_escape_string($conn, $data['sector']);
    $priority = mysqli_real_escape_string($conn, $data['priority'] ?? 'medium');

    $query = "INSERT INTO complain (name, email, phone, location, title, description, complaint_sector, priority, status, created_at) 
              VALUES ('$name', '$email', '$phone', '$location', '$title', '$description', '$sector', '$priority', 'pending', NOW())";

    return mysqli_query($conn, $query);
}

// Update updateComplaint function to match the complain table structure
function updateComplaint($conn, $id, $data) {
    $id = intval($id);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $location = mysqli_real_escape_string($conn, $data['location']);
    $title = mysqli_real_escape_string($conn, $data['title']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $sector = mysqli_real_escape_string($conn, $data['sector']);
    $priority = mysqli_real_escape_string($conn, $data['priority']);
    $status = mysqli_real_escape_string($conn, $data['status']);
    $assigned_to = mysqli_real_escape_string($conn, $data['assigned_to'] ?? null);

    $query = "UPDATE complain SET 
              name = '$name', 
              email = '$email', 
              phone = '$phone', 
              location = '$location', 
              title = '$title', 
              description = '$description', 
              complaint_sector = '$sector', 
              priority = '$priority', 
              status = '$status', 
              assigned_to = " . ($assigned_to ? "'$assigned_to'" : "NULL") . ",
              updated_at = NOW() 
              WHERE id = $id";

    return mysqli_query($conn, $query);
}

// Function to create user
function createUser($conn, $data) {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $query = "INSERT INTO user (name, email, phone, address, password, status, created_at) 
              VALUES ('$name', '$email', '$phone', '$address', '$password', 'active', NOW())";
    
    return mysqli_query($conn, $query);
}

// Function to update user
function updateUser($conn, $id, $data) {
    $id = intval($id);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $status = mysqli_real_escape_string($conn, $data['status']);
    
    $query = "UPDATE user SET 
              name = '$name', 
              email = '$email', 
              phone = '$phone', 
              address = '$address', 
              status = '$status', 
              updated_at = NOW() 
              WHERE id = $id";
    
    if (isset($data['password']) && !empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $query = "UPDATE user SET 
                  name = '$name', 
                  email = '$email', 
                  phone = '$phone', 
                  address = '$address', 
                  password = '$password',
                  status = '$status', 
                  updated_at = NOW() 
                  WHERE id = $id";
    }
    
    return mysqli_query($conn, $query);
}

// Function to delete user
function deleteUser($conn, $id) {
    $id = intval($id);
    $query = "DELETE FROM user WHERE id = $id";
    return mysqli_query($conn, $query);
}

// Function to create sector
function createSector($conn, $data) {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $department_head = mysqli_real_escape_string($conn, $data['department_head']);
    $contact_email = mysqli_real_escape_string($conn, $data['contact_email']);
    $contact_phone = mysqli_real_escape_string($conn, $data['contact_phone']);
    
    $query = "INSERT INTO sectors (name, description, department_head, contact_email, contact_phone, status, created_at) 
              VALUES ('$name', '$description', '$department_head', '$contact_email', '$contact_phone', 'active', NOW())";
    
    return mysqli_query($conn, $query);
}

// Function to update sector
function updateSector($conn, $id, $data) {
    $id = intval($id);
    $name = mysqli_real_escape_string($conn, $data['name']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $department_head = mysqli_real_escape_string($conn, $data['department_head']);
    $contact_email = mysqli_real_escape_string($conn, $data['contact_email']);
    $contact_phone = mysqli_real_escape_string($conn, $data['contact_phone']);
    $status = mysqli_real_escape_string($conn, $data['status']);
    
    $query = "UPDATE sectors SET 
              name = '$name', 
              description = '$description', 
              department_head = '$department_head', 
              contact_email = '$contact_email', 
              contact_phone = '$contact_phone', 
              status = '$status' 
              WHERE id = $id";
    
    return mysqli_query($conn, $query);
}

// Function to delete sector
function deleteSector($conn, $id) {
    $id = intval($id);
    $query = "DELETE FROM sectors WHERE id = $id";
    return mysqli_query($conn, $query);
}

// Get dashboard statistics
function getDashboardStats($conn) {
    $stats = [];
    
    // Total complaints
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM complain");
    $stats['total_complaints'] = mysqli_fetch_assoc($result)['total'];
    
    // Resolved complaints
    $result = mysqli_query($conn, "SELECT COUNT(*) as resolved FROM complain WHERE status = 'resolved'");
    $stats['resolved_count'] = mysqli_fetch_assoc($result)['resolved'];
    
    // Pending complaints
    $result = mysqli_query($conn, "SELECT COUNT(*) as pending FROM complain WHERE status = 'pending' OR status IS NULL");
    $stats['pending_count'] = mysqli_fetch_assoc($result)['pending'];
    
    // Overdue complaints (older than 7 days and not resolved)
    $result = mysqli_query($conn, "SELECT COUNT(*) as overdue FROM complain WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY) AND (status != 'resolved' AND status != 'closed')");
    $stats['overdue_count'] = mysqli_fetch_assoc($result)['overdue'];
    
    return $stats;
}

// Get recent complaints
function getRecentComplaints($conn, $limit = 10) {
    $query = "SELECT c.*, c.name FROM complain c 
              ORDER BY c.created_at DESC LIMIT " . intval($limit);
    $result = mysqli_query($conn, $query);
    
    $complaints = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $complaints[] = $row;
    }
    
    return $complaints;
}

// Get sector statistics
function getSectorStats($conn) {
    $query = "SELECT complaint_sector AS sector, COUNT(*) as count FROM complain WHERE complaint_sector IS NOT NULL GROUP BY complaint_sector ORDER BY count DESC";
    $result = mysqli_query($conn, $query);

    $stats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $stats[$row['sector']] = $row['count'];
    }

    // Add default sectors if none exist
    if (empty($stats)) {
        $stats = [
            'Municipal' => 0,
            'Police' => 0,
            'Healthcare' => 0,
            'Education' => 0,
            'Transport' => 0
        ];
    }

    return $stats;
}

// Get status statistics
function getStatusStats($conn) {
    $query = "SELECT 
                CASE 
                    WHEN status IS NULL OR status = '' THEN 'Pending'
                    WHEN status = 'in_progress' THEN 'In Progress'
                    WHEN status = 'resolved' THEN 'Resolved'
                    WHEN status = 'closed' THEN 'Closed'
                    ELSE status
                END as status_label,
                COUNT(*) as count 
              FROM complain 
              GROUP BY status_label 
              ORDER BY count DESC";
    
    $result = mysqli_query($conn, $query);
    
    $stats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $stats[$row['status_label']] = $row['count'];
    }
    
    // Add default statuses if none exist
    if (empty($stats)) {
        $stats = [
            'Pending' => 0,
            'In Progress' => 0,
            'Resolved' => 0,
            'Overdue' => 0
        ];
    }
    
    return $stats;
}

// Get all complaints with pagination
function getAllComplaints($conn, $page = 1, $limit = 20, $status_filter = null) {
    $offset = ($page - 1) * $limit;
    
    $where_clause = "";
    if ($status_filter && $status_filter !== 'all') {
        $where_clause = "WHERE status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
    }
    
    $query = "SELECT * FROM complain 
              $where_clause
              ORDER BY created_at DESC 
              LIMIT $limit OFFSET $offset";
    
    $result = mysqli_query($conn, $query);
    
    $complaints = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $complaints[] = $row;
    }
    
    return $complaints;
}

// Get complaint by ID
function getComplaintById($conn, $id) {
    $id = intval($id);
    $query = "SELECT * FROM complain WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Time ago function
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    if ($time < 31536000) return floor($time/2592000) . ' months ago';
    
    return floor($time/31536000) . ' years ago';
}

// Get all users
function getAllUsers() {
    global $conn;
    
    $query = "SELECT * FROM user ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    
    $users = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    return $users;
}

// Function to get total admins
function getTotalAdmins($conn) {
    $query = "SELECT COUNT(*) as total_admins FROM admin WHERE status = '1'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total_admins'];
}

// Update complaint status (we'll need to add status field to database)
function updateComplaintStatus($id, $status) {
    global $conn;
    
    // First, let's add the status column if it doesn't exist
    $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'status'");
    if (mysqli_num_rows($checkColumn) == 0) {
        mysqli_query($conn, "ALTER TABLE complain ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
    }
    
    $id = mysqli_real_escape_string($conn, $id);
    $status = mysqli_real_escape_string($conn, $status);
    
    $query = "UPDATE complain SET status = '$status' WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

// Add priority field and update complaint priority
function updateComplaintPriority($id, $priority) {
    global $conn;
    
    // Add priority column if it doesn't exist
    $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'priority'");
    if (mysqli_num_rows($checkColumn) == 0) {
        mysqli_query($conn, "ALTER TABLE complain ADD COLUMN priority VARCHAR(10) DEFAULT 'medium'");
    }
    
    $id = mysqli_real_escape_string($conn, $id);
    $priority = mysqli_real_escape_string($conn, $priority);
    
    $query = "UPDATE complain SET priority = '$priority' WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

// Add assigned_to field and assign complaint
function assignComplaint($id, $officer) {
    global $conn;
    
    // Add assigned_to column if it doesn't exist
    $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'assigned_to'");
    if (mysqli_num_rows($checkColumn) == 0) {
        mysqli_query($conn, "ALTER TABLE complain ADD COLUMN assigned_to VARCHAR(50) DEFAULT NULL");
    }
    
    $id = mysqli_real_escape_string($conn, $id);
    $officer = mysqli_real_escape_string($conn, $officer);
    
    $query = "UPDATE complain SET assigned_to = '$officer' WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

// Get complaints by sector for analytics
function getComplaintsBySector() {
    global $conn;
    
    $query = "SELECT complaint_sector, COUNT(*) as count FROM complain GROUP BY complaint_sector";
    $result = mysqli_query($conn, $query);
    
    $sectors = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $sectors[] = $row;
    }
    
    return $sectors;
}

// Get complaints by status for analytics
function getComplaintsByStatus() {
    global $conn;
    
    // Add status column if it doesn't exist
    $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'status'");
    if (mysqli_num_rows($checkColumn) == 0) {
        mysqli_query($conn, "ALTER TABLE complain ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
    }
    
    $query = "SELECT status, COUNT(*) as count FROM complain GROUP BY status";
    $result = mysqli_query($conn, $query);
    
    $statuses = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $statuses[] = $row;
    }
    
    return $statuses;
}

// Delete complaint
function deleteComplaint($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    
    // Delete associated photos first
    mysqli_query($conn, "DELETE FROM photo WHERE c_id = '$id'");
    
    // Delete complaint
    $query = "DELETE FROM complain WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

// Add created_at field for better tracking
function addTimestampFields() {
    global $conn;
    
    // Add created_at column if it doesn't exist
    $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'created_at'");
    if (mysqli_num_rows($checkColumn) == 0) {
        mysqli_query($conn, "ALTER TABLE complain ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }
    
    // Add updated_at column if it doesn't exist
    $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM complain LIKE 'updated_at'");
    if (mysqli_num_rows($checkColumn) == 0) {
        mysqli_query($conn, "ALTER TABLE complain ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
}

// Initialize database improvements
addTimestampFields();
?>
