<?php
session_start();
require_once '../dbconfig.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get action from request
$action = $_REQUEST['action'] ?? '';

// Handle different actions
switch ($action) {
    case 'getUsers':
        getUsers($conn);
        break;
    
    case 'getUser':
        getUser($conn);
        break;
    
    case 'createUser':
        createUser($conn);
        break;
    
    case 'updateUser':
        updateUser($conn);
        break;
    
    case 'deleteUser':
        deleteUser($conn);
        break;
    
    case 'updateUserStatus':
        updateUserStatus($conn);
        break;
    
    case 'getComplaints':
        getComplaints($conn);
        break;
    
    case 'getComplaint':
        getComplaint($conn);
        break;
    
    case 'updateComplaintStatus':
        updateComplaintStatus($conn);
        break;
    
    case 'deleteComplaint':
        deleteComplaint($conn);
        break;
    
    case 'getDashboardStats':
        getDashboardStats($conn);
        break;
    
    case 'exportReport':
        exportReport($conn);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

// Function to get all users
function getUsers($conn) {
    $query = "SELECT id, name, email, phone, COALESCE(u_status, 'active') as status 
              FROM user 
              WHERE 1=1";
    
    // Apply status filter
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $status = mysqli_real_escape_string($conn, $_GET['status']);
        $query .= " AND COALESCE(u_status, 'active') = '$status'";
    }
    
    // Apply search filter
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%')";
    }
    
    $query .= " ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
        return;
    }
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['address'] = 'N/A'; // Default value since column doesn't exist
        $row['created_at'] = date('Y-m-d H:i:s'); // Default to current date
        $users[] = $row;
    }
    
    echo json_encode(['success' => true, 'users' => $users]);
}

// Function to get single user
function getUser($conn) {
    $id = intval($_GET['id']);
    $query = "SELECT id, name, email, phone FROM user WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $row['address'] = 'N/A'; // Default value since column doesn't exist
        $row['created_at'] = date('Y-m-d H:i:s'); // Default to current date
        $row['status'] = 'active'; // Default status
        echo json_encode(['success' => true, 'user' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
}

// Function to create user
function createUser($conn) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query = "INSERT INTO user (name, email, phone, pass) 
              VALUES ('$name', '$email', '$phone', '$password')";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create user: ' . mysqli_error($conn)]);
    }
}

// Function to update user
function updateUser($conn) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    $query = "UPDATE user SET name = '$name', email = '$email', phone = '$phone'";
    
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $query .= ", pass = '$password'";
    }
    
    $query .= " WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user: ' . mysqli_error($conn)]);
    }
}

// Function to delete user
function deleteUser($conn) {
    $id = intval($_POST['id']);
    $query = "DELETE FROM user WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user: ' . mysqli_error($conn)]);
    }
}

// Function to update user status
function updateUserStatus($conn) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Update u_status field in user table
    $query = "UPDATE user SET u_status = '$status' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'User status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status: ' . mysqli_error($conn)]);
    }
}

// Function to get all complaints
function getComplaints($conn) {
    $page = intval($_GET['page'] ?? 1);
    $limit = intval($_GET['limit'] ?? 10);
    $offset = ($page - 1) * $limit;
    
    $query = "SELECT c.*, a.email AS assigned_to_email 
              FROM complain c 
              LEFT JOIN admin a ON c.assigned_to = a.id 
              ORDER BY c.created_at DESC 
              LIMIT $limit OFFSET $offset";
    
    $result = mysqli_query($conn, $query);
    
    $complaints = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $complaints[] = $row;
    }
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM complain";
    $countResult = mysqli_query($conn, $countQuery);
    $total = mysqli_fetch_assoc($countResult)['total'];
    
    echo json_encode([
        'success' => true, 
        'complaints' => $complaints,
        'total' => $total,
        'page' => $page,
        'pages' => ceil($total / $limit)
    ]);
}

// Function to get single complaint
function getComplaint($conn) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM complain WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Fetch photos for this complaint
        $photoQuery = "SELECT path FROM photo WHERE c_id = $id";
        $photoResult = mysqli_query($conn, $photoQuery);
        $photos = [];
        while ($photoRow = mysqli_fetch_assoc($photoResult)) {
            $photos[] = $photoRow['path'];
        }
        $row['photos'] = $photos;
        
        echo json_encode(['success' => true, 'complaint' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Complaint not found']);
    }
}

// Function to update complaint status
function updateComplaintStatus($conn) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "UPDATE complain SET status = '$status', updated_at = NOW() WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        // Also update complaint_status_summary if exists
        $summaryQuery = "UPDATE complaint_status_summary SET status = '$status' WHERE compid = $id";
        mysqli_query($conn, $summaryQuery);
        
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status: ' . mysqli_error($conn)]);
    }
}

// Function to delete complaint
function deleteComplaint($conn) {
    $id = intval($_POST['id']);
    
    // Delete from complain table
    $query = "DELETE FROM complain WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        // Also delete from complaint_status_summary
        $summaryQuery = "DELETE FROM complaint_status_summary WHERE compid = $id";
        mysqli_query($conn, $summaryQuery);
        
        echo json_encode(['success' => true, 'message' => 'Complaint deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete complaint: ' . mysqli_error($conn)]);
    }
}

// Function to get dashboard statistics
function getDashboardStats($conn) {
    $stats = [];
    
    // Total complaints
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM complain");
    $stats['total_complaints'] = mysqli_fetch_assoc($result)['total'];
    
    // Pending complaints
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM complain WHERE status = 'pending'");
    $stats['pending_complaints'] = mysqli_fetch_assoc($result)['total'];
    
    // Resolved complaints
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM complain WHERE status = 'resolved'");
    $stats['resolved_complaints'] = mysqli_fetch_assoc($result)['total'];
    
    // Total users
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM user");
    $stats['total_users'] = mysqli_fetch_assoc($result)['total'];
    
    echo json_encode(['success' => true, 'stats' => $stats]);
}

// Function to export report
function exportReport($conn) {
    $type = $_GET['type'] ?? 'csv';
    $query = "SELECT * FROM complain ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    
    if ($type === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="complaints_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Title', 'Description', 'Sector', 'Status', 'Priority', 'Created At']);
        
        // Add data
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['id'],
                $row['name'],
                $row['email'],
                $row['phone'],
                $row['title'],
                $row['description'],
                $row['complaint_sector'],
                $row['status'],
                $row['priority'],
                $row['created_at']
            ]);
        }
        
        fclose($output);
    }
}
?>
