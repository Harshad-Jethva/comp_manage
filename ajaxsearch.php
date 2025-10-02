<?php
include("dbconfig.php");

// Get filter parameters
$status = isset($_GET['status']) ? $_GET['status'] : '';
$sector = isset($_GET['sector']) ? $_GET['sector'] : '';
$priority = isset($_GET['priority']) ? $_GET['priority'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare the SQL query
$sql = "SELECT id, title, description, complaint_sector, status, priority, created_at, photo FROM `complain` WHERE 1";

if (!empty($query)) {
    $sql .= " AND (title LIKE ? OR description LIKE ? OR id LIKE ?)";
}
if (!empty($status)) {
    $sql .= " AND status = ?";
}
if (!empty($sector)) {
    $sql .= " AND complaint_sector = ?";
}
if (!empty($priority)) {
    $sql .= " AND priority = ?";
}
if (!empty($date)) {
    $sql .= " AND DATE(created_at) = ?";
}

$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$params = [];
if (!empty($query)) {
    $searchTerm = "%$query%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}
if (!empty($status)) {
    $params[] = $status;
}
if (!empty($sector)) {
    $params[] = $sector;
}
if (!empty($priority)) {
    $params[] = $priority;
}
if (!empty($date)) {
    $params[] = $date;
}

$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$complaints = [];
while ($row = $result->fetch_assoc()) {
    $row['photo'] = !empty($row['photo']) ? explode(',', $row['photo']) : [];
    $complaints[] = $row;
}

// Return results as JSON
header('Content-Type: application/json');
echo json_encode($complaints);
?>